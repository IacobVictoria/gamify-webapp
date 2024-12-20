<?php

namespace App\Http\Controllers;

use App\Interfaces\PdfGeneratorServiceInterface;
use App\Models\Event;
use App\Models\QrCodeEvent;
use App\Models\Report;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ReportController extends Controller
{
    protected $pdfGenerator;

    public function __construct(PdfGeneratorServiceInterface $pdfGenerator)
    {
        $this->pdfGenerator = $pdfGenerator;
    }
    public function index()
    {

        $reports = [
            'qr_codes' => $this->getQRCodeReports(),
            'list_participants' => Report::where('type', 'participants')->get(),
            'supplier_invoices' => Report::where('type', 'supplier_invoice')->get(),
        ];

        return Inertia::render('Admin/Reports/Index', [
            'reports' => $reports,
        ]);
    }

    private function getQRCodeReports()
    {
        return Event::all()->map(function ($event) {
            $qrFileName = "events/qr_codes_{$event->id}.png";
            $qrCodeUrl = Storage::disk('s3')->url($qrFileName);
            return [
                'title' => $event->title,
                'qr_code_url' => $qrCodeUrl,
            ];
        });
    }

    public function showQRCodeReports()
    {
        $qrCodeReports = QrCodeEvent::with('event') // Include the associated event details
            ->get()
            ->map(function ($qrCodeEvent) {
                return [
                    'title' => $qrCodeEvent->event->title,  // Event title from the related Event model
                    'qr_code_url' => $qrCodeEvent->image_url, // Image URL from the `qr_codes_events` table
                ];
            });

        // Pass the reports to the Inertia view
        return Inertia::render('Admin/Reports/ShowQRCodeReports', [
            'reports' => $qrCodeReports,
        ]);
    }
    public function showParticipantsList()
    {

        $participantsReports = Report::where('type', 'participants')->get()->map(function ($report) {
            return [
                'title' => $report->title,
                's3_path' => $report->s3_path,
            ];
        });

        return Inertia::render('Admin/Reports/ShowParticipantsList', [
            'reports' => $participantsReports,
        ]);
    }

    public function showSupplierInvoicesList()
    {
        $supplierInvoices = Report::where('type', 'supplier_invoice')->get()->map(function ($report) {
            return [
                'title' => $report->title,
                's3_path' => $report->s3_path,
            ];
        });

        return Inertia::render('Admin/Reports/ShowSupplierInvoicesList', [
            'reports' => $supplierInvoices,
        ]);
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
            's3_path' => $filePath,
        ]);

        return redirect()->back()->with('message', 'PDF generated successfully!');
    }
}
