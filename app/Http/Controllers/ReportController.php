<?php

namespace App\Http\Controllers;

use App\Interfaces\PdfGeneratorServiceInterface;
use App\Models\Event;
use App\Models\Report;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $pdfGenerator;

    public function __construct(PdfGeneratorServiceInterface $pdfGenerator)
    {
        $this->pdfGenerator = $pdfGenerator;
    }

    public function downloadParticipants($eventId)
    {
        $event = Event::with('participants')->findOrFail($eventId);
        $eventDetails = [
            'title' => $event->title,
            'description' => $event->description,
            'start' => $event->start,
            'end' => $event->end,
        ];

        $participants = $event->participants->map(function ($participant) {
            return [
                'name' => $participant->user->name,
                'email' => $participant->user->email,
                'confirmed' => $participant->confirmed,
            ];
        })->toArray();

        // Calculăm numărul de participanți care au confirmat și cei care nu au confirmat
        $confirmedCount = $event->participants->where('confirmed', 1)->count();
        $notConfirmedCount = $event->participants->where('confirmed', 0)->count();
        $totalParticipants = $event->participants->count();

        // Calculăm procentul celor care au confirmat
        $confirmationPercentage = $totalParticipants > 0 ? ($confirmedCount / $totalParticipants) * 100 : 0;

        $filename = "participants_event_{$eventId}.pdf";

        // Generăm PDF-ul și obținem calea fișierului S3
        $filePath = $this->pdfGenerator->generateParticipantsListPdf(
            $eventDetails,
            $participants,
            $filename,
            $confirmedCount,
            $notConfirmedCount,
            $confirmationPercentage,
            $totalParticipants
        );

        // Salvăm raportul în baza de date
        Report::create([
            'id' => Uuid::uuid(),
            'type' => 'participants',
            'title' => "Lista Participanților - {$event->title}",
            's3_path' => $filePath, // Calea fișierului PDF pe S3
        ]);

        return redirect()->back()->with('message', 'PDF generated successfully!');
    }
}
