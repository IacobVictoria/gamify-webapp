<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\Models\QrCodeEvent;
use App\Models\Report;
use App\Services\DompdfGeneratorService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class EventController extends Controller
{
    protected $pdfService;
    public function __construct(DompdfGeneratorService $pdfService)
    {
        $this->pdfService = $pdfService;
    }
    public function index()
    {
        // Obține reducerile active (discounts)
        $activeDiscounts = Event::where('type', 'discount')
            ->where('status', 'OPEN')
            ->where('start', '<=', now())  // Reduceri care au început deja
            ->where('end', '>=', now())
            ->where('is_published', true)  // Reduceri care nu au expirat
            ->get();

        // Obține evenimentele viitoare (event)
        $activeEvents = Event::where('type', 'event')
            ->where('status', 'OPEN')
            ->where('start', '>', now())
            ->where('is_published', true)  // Evenimente care vor începe în viitor
            ->get();

        $inProgressEvents = Event::where('type', 'event')
            ->where('start', '<=', now())  
            ->where('end', '>=', now())
            ->where('is_published', true)  
            ->get();

        foreach ($activeDiscounts as $event) {
            if ($event->type === 'discount' && $event->details) {
                $event->details = json_decode($event->details, true);
            }
        }


        return Inertia::render('Events/Index', [
            'activeDiscounts' => $activeDiscounts,
            'activeEvents' => $activeEvents,
            'inProgressEvents' => $inProgressEvents,
        ]);
    }

    public function generateParticipantsPdfPreview($eventId)
    {
        $event = Event::findOrFail($eventId);

        // Construim titlul raportului așa cum a fost salvat în baza de date
        $reportTitle = "Lista Participanților - {$event->title}";
    
        // Căutăm raportul în baza de date după titlu
        $report = Report::where('type', 'participants')
            ->where('title', $reportTitle)
            ->first();

        if ($report) {
            // Dacă raportul există, returnăm URL-ul din s3_path
            $pdfUrl = $report->s3_path;
    
            return response()->json(['pdf_url' => $pdfUrl]);
        } else {
            // Dacă raportul nu există, returnăm un mesaj de eroare
            return response()->json(['message' => 'The event has not ended yet, or the participant list is unavailable.'], 200);
        }
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);

        $eventStart = Carbon::parse($event->start)->setTimezone('Europe/Bucharest');


        // Calculăm timpul rămas până la începerea evenimentului
        $now = Carbon::now()->setTimezone('Europe/Bucharest'); // Asigură-te că folosești fusul orar corect
        $timeUntilEvent = $eventStart->diff($now); // Obținem diferența în obiect DateInterval

        // Calculăm minutele din diferență
        $timeUntilEventInMinutes = $timeUntilEvent->d * 24 * 60 + $timeUntilEvent->h * 60 + $timeUntilEvent->i;
        //asa returneaza diff()   cu h, d, i

        // Verificăm dacă evenimentul este blocat
       $isEventLocked = $timeUntilEventInMinutes <= 10 || $eventStart <= $now;
      //  $isEventLocked =$eventStart <= $now;


        $qrCode = QrCodeEvent::where('event_id', $event->id)->first();
        $isParticipant = Participant::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->exists();
        $isParticipantConfirmed = Participant::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->where('confirmed', true)
            ->exists();

        return Inertia::render('Events/Show', [
            'event' => $event,
            'qrCode' => $qrCode->image_url,
            'isParticipant' => $isParticipant,
            'isEventLocked' => $isEventLocked,
            'isParticipantConfirmed' => $isParticipantConfirmed
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
