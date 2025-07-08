<?php

namespace App\Services\Reports;

use App\Helpers\PeriodHelper;
use App\Models\UserBadge;
use App\Models\UserMedal;
use Carbon\Carbon;

class RewardsActivityReportService
{
    public function getReportByPeriod(string $period, Carbon $meetingDate): array
    {
        $dateRange = PeriodHelper::getPeriodRange($period, $meetingDate);
        $startDate = $dateRange['start_date'];
        $endDate = $dateRange['end_date'];

        return [
            'period' => $period,
            'startDate' => Carbon::parse($dateRange['start_date'])->format('d.m.Y'),
            'endDate' => Carbon::parse($dateRange['end_date'])->format('d.m.Y'),
            'average_time_to_first_medal' => $this->getAverageTimeToFirstMedal($startDate, $endDate),
            'easiest_and_hardest_badges' => $this->getEasiestAndHardestBadges($startDate, $endDate),
            'average_badges_per_category' => $this->getAverageBadgesPerCategory($startDate, $endDate),
        ];
    }

    /**
     * Calculează timpul mediu până la obținerea primei medalii într-o perioadă selectată.
     * Se iau toți utilizatorii care au primit cel puțin o medalie 
     * și se calculează media zilelor dintre data înregistrării și data primei medalii.
     * 
     * @return int Numărul mediu de zile.
     */
    private function getAverageTimeToFirstMedal($startDate, $endDate): int
    {
        $users = UserMedal::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('user_id, MIN(created_at) as first_medal_date')
            ->groupBy('user_id')
            ->get()
            ->map(
                fn($userMedal) => $userMedal->user ?
                $userMedal->user->created_at->diffInDays($userMedal->first_medal_date) : null
            )
            ->filter(); // Elimină valorile null

        return $users->isNotEmpty() ? round($users->avg()) : 0;
    }

    /**
     * Returnează insignele cele mai ușor și cele mai greu de obținut într-o perioadă selectată.
     * Se analizează toate insignele obținute până acum și se identifică 
     * cele mai comune (cel mai ușor obținute) și cele mai rare.
     * 
     * @return array ['easiest' => [...], 'hardest' => [...]]
     */
    private function getEasiestAndHardestBadges($startDate, $endDate): array
    {
        $badges = UserBadge::whereBetween('awarded_at', [$startDate, $endDate])
            ->selectRaw('badge_id, COUNT(*) as obtained_count')
            ->groupBy('badge_id')
            ->with('badge:id,name')
            ->get()
            ->map(fn($badge) => [
                'name' => $badge->badge->name ?? 'Unknown',
                'obtained_count' => $badge->obtained_count
            ])
            ->sortBy('obtained_count');

        return [
            'easiest' => $badges->last() ?? null, // Cel mai frecvent obținut
            'hardest' => $badges->first() ?? null // Cel mai rar obținut
        ];
    }

    /**
     * Calculează media de insigne obținute per categorie într-o perioadă selectată.
     * Se analizează câte insigne sunt obținute în fiecare categorie și se face o medie.
     * 
     * @return array ['category' => avg_badges]
     */
    public function getAverageBadgesPerCategory($startDate, $endDate): array
    {
        return UserBadge::whereBetween('awarded_at', [$startDate, $endDate])->with('badge')
            ->get()
            ->groupBy(fn($badge) => $badge->badge->category ?? 'Unknown') // Grupăm după câmpul text `category`
            ->map(fn($group) => round($group->count() / max(1, $group->unique('user_id')->count()))) // Medie per utilizator
            ->toArray();
    }

}
