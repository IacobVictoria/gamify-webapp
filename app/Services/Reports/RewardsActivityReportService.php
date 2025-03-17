<?php

namespace App\Services\Reports;

use App\Models\UserBadge;
use App\Models\UserMedal;

class RewardsActivityReportService
{
    public function getMonthlyReport(): array
    {
        return [
            'month' => now()->format('F Y'),
            'average_time_to_first_medal' => $this->getAverageTimeToFirstMedal(),
            'easiest_and_hardest_badges' => $this->getEasiestAndHardestBadges(),
            'average_badges_per_category' => $this->getAverageBadgesPerCategory(),
        ];
    }

    /**
     * Calculează timpul mediu până la obținerea primei medalii.
     * Se iau toți utilizatorii care au primit cel puțin o medalie 
     * și se calculează media zilelor dintre data înregistrării și data primei medalii.
     * 
     * @return int Numărul mediu de zile.
     */
    private function getAverageTimeToFirstMedal(): int
    {
        $users = UserMedal::with('user')
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
     * Returnează insignele cele mai ușor și cele mai greu de obținut.
     * Se analizează toate insignele obținute până acum și se identifică 
     * cele mai comune (cel mai ușor obținute) și cele mai rare.
     * 
     * @return array ['easiest' => [...], 'hardest' => [...]]
     */
    private function getEasiestAndHardestBadges(): array
    {
        $badges = UserBadge::selectRaw('badge_id, COUNT(*) as obtained_count')
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
     * Calculează media de insigne obținute per categorie.
     * Se analizează câte insigne sunt obținute în fiecare categorie și se face o medie.
     * 
     * @return array ['category' => avg_badges]
     */
    private function getAverageBadgesPerCategory(): array
    {
        return UserBadge::with('badge')
            ->get()
            ->groupBy(fn($badge) => $badge->badge->category ?? 'Unknown') // Grupăm după câmpul text `category`
            ->map(fn($group) => round($group->count() / max(1, $group->unique('user_id')->count()))) // Medie per utilizator
            ->toArray();
    }

}
