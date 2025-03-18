<?php 

namespace App\Strategies\Badges\QrScanProduct;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;
use App\Models\QrCodeScan;
use Carbon\Carbon;

class ScanStreakBadgeStrategy implements BadgeStrategyInterface
{
    public function appliesTo(User $user): bool
    {
        $scans = QrCodeScan::where('user_id', $user->id)
            ->whereBetween('scanned_at', [Carbon::now()->subDays(7), Carbon::now()])
            ->distinct()
            ->count();

        return $scans >= 7;
    }

    public function getBadgeName(): string
    {
        return 'Scan Streak';
    }
}
