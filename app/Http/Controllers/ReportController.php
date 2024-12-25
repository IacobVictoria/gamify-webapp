<?php

namespace App\Http\Controllers;

use App\Interfaces\PdfGeneratorServiceInterface;
use App\Models\Event;
use App\Models\QrCodeEvent;
use App\Models\Report;
use Carbon\Carbon;
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

    public function showQRCodeReports(Request $request)
    {
        $title = $request->get('title', '');

        $qrCodeReports = QrCodeEvent::with('event')
            ->when($title, function ($query, $title) {
                // Filtrarea pe titlu
                return $query->whereHas('event', function ($q) use ($title) {
                    $q->where('title', 'like', '%' . $title . '%');
                });
            })
            ->get()
            ->map(function ($qrCodeEvent) {
                return [
                    'title' => $qrCodeEvent->event->title,
                    'qr_code_url' => $qrCodeEvent->image_url,
                ];
            });

        return Inertia::render('Admin/Reports/ShowQRCodeReports', [
            'reports' => $qrCodeReports,
            'filters' => ['title' => $title],
        ]);
    }


    public function showParticipantsList(Request $request)
    {
        $year = $request->get('year', Carbon::now()->format('Y'));
        $month = $request->get('month', Carbon::now()->format('m'));

        $participantsReports = Report::where('type', 'participants')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get()
            ->map(function ($report) {
                return [
                    'title' => $report->title,
                    's3_path' => $report->s3_path,
                    'week' => Carbon::parse($report->created_at)->format('W'),
                    'created_at' => $report->created_at->format('Y-m-d'),
                ];
            });

        // Gruparea pe săptămâni
        $groupedReports = $participantsReports->groupBy('week');

        return Inertia::render('Admin/Reports/ShowParticipantsList', [
            'groupedReports' => $groupedReports,
            'year' => $year,
            'month' => $month,
        ]);
    }



    public function showSupplierInvoicesList(Request $request)
    {
        $year = $request->input('year', Carbon::now()->format('Y'));
        $month = $request->input('month', Carbon::now()->format('m'));

        // Selectarea facturilor pentru anul și luna specificate
        $supplierInvoices = Report::where('type', 'supplier_invoice')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get()
            ->map(function ($report) {
                return [
                    'title' => $report->title,
                    's3_path' => $report->s3_path,
                    'week' => Carbon::parse($report->created_at)->format('W'),
                    'year' => Carbon::parse($report->created_at)->format('Y'),
                ];
            });

        // Gruparea pe săptămâni
        $groupedInvoices = $supplierInvoices->groupBy('week');

        return Inertia::render('Admin/Reports/ShowSupplierInvoicesList', [
            'groupedReports' => $groupedInvoices,
            'year' => $year,
            'month' => $month,
        ]);
    }
   
}
