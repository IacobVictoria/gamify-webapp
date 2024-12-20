<?php

namespace App\Interfaces;
interface PdfGeneratorServiceInterface
{
    /**
     * Generează un PDF cu detaliile evenimentului și lista participanților.
     *
     * @param array $event Detalii despre eveniment (titlu, descriere, etc.).
     * @param array $participants Lista participanților.
     * @param string $filename Numele fișierului generat.
     * @return string Calea către fișierul PDF generat.
     */
    public function generateParticipantsListPdf(array $event, array $participants, string $filenameint, int $confirmedCount, int $notConfirmedCount, float $confirmationPercentage,int $totalParticipants): string;
    public function generateInvoicePdf(array $invoiceData, string $filename): string;

    /**
     * Generează un PDF cu raportul zilnic.
     *
     * @param array $reportData Datele pentru raportul zilnic.
     * @param string $filename Numele fișierului generat.
     * @return string Calea către fișierul PDF generat.
     */
    // public function generateDailyReportPdf(array $reportData, string $filename): string;

    /**
     * Generează un PDF cu raportul săptămânal.
     *
     * @param array $reportData Datele pentru raportul săptămânal.
     * @param string $filename Numele fișierului generat.
     * @return string Calea către fișierul PDF generat.
     */
    // public function generateWeeklyReportPdf(array $reportData, string $filename): string;
}
