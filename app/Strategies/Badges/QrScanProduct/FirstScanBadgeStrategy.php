<?php 

namespace App\Strategies\Badges\QrScanProduct;

use App\Interfaces\BadgeStrategyInterface;
use App\Models\User;
use App\Models\QrCodeScan;

class FirstScanBadgeStrategy implements BadgeStrategyInterface
{
    public function appliesTo(User $user): bool
    {
        return QrCodeScan::where('user_id', $user->id)->count() === 1;
    }

    public function getBadgeName(): string
    {
        return 'First Scan';
    }
}
