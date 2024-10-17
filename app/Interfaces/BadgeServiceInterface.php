<?php

namespace App\Interfaces;
use App\Models\User;
interface BadgeServiceInterface
{
    public function reviewerBadges(User $user);
    public function shoopingBadges(User $user);
    public function awardActiveCommenterBadge(User $user);
    public function awardTrustedCommenterBadge(?User $user);
    public function awardTopReviewerBadge(User $user);

    public function awardProductExpertBadge(User $user);

    public function awardTrustedReviewerBadge(User $user);

    public function awardActiveReviewerBadge(User $user);

    public function awardPioneerBadge(User $user);

    public function assignBadge(User $user, string $badgeName);
    public function awardActiveShoppingBadge(User $user);
    public function awardMonthlyShoppingBadge(User $user);
}