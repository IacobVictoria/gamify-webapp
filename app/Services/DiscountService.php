<?php

namespace App\Services;

use App\Events\UserEligibleForDiscountEvent;
use App\Models\User;
use Illuminate\Support\Str;

class DiscountService
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;

    }
    /**
     * Returnează discount-urile disponibile pentru utilizator
     */
    public function getAvailableDiscounts(User $user)
    {
        // Pragurile discounturilor
        $discountLevels = [
            200 => 20,
            500 => 25,
            1000 => 30
        ];
    
        $usedDiscounts = json_decode($user->used_discounts, true) ?? [];
        $availableDiscounts = [];
    
        foreach ($discountLevels as $points => $discount) {
            if ($user->score >= $points) {
                // Verificăm dacă utilizatorul a folosit deja acest discount
                $isUsed = in_array($discount, $usedDiscounts);
                $code = strtoupper(substr(md5($user->id . $discount . time()), 0, 8)); // Generăm un cod unic
    
                $availableDiscounts[] = [
                    'discount' => $discount,
                    'used' => $isUsed,
                    'code' => $isUsed ? 'Utilizat' : $code
                ];
            }
        }
    
        return $availableDiscounts;
    }
    
    /**
     * Verifică dacă utilizatorul a atins un prag de discount și notifică
     */
    public function checkAndNotifyBonusAvailability(User $user)
    {
        $discountLevels = [
            200 => 20,
            500 => 25,
            1000 => 30
        ];
    
        $usedDiscounts = json_decode($user->used_discounts, true) ?? [];

        foreach ($discountLevels as $points => $discount) {
            if ($user->score >= $points && !isset($usedDiscounts[$points])) {
                // Generăm un cod promo unic
                $code = strtoupper(Str::random(10));

                // Salvăm codul în user_discounts fără să îl marcăm ca folosit
                $usedDiscounts[$points] = [
                    'code' => $code,
                    'discount' => $discount,
                    'used' => false
                ];

                $user->used_discounts = json_encode($usedDiscounts);
                $user->save();

                // Notificare despre noul discount
                broadcast(new UserEligibleForDiscountEvent($user, $discount, $this->notificationService));
            }
        }
    }

    /**
     * Marchează un discount ca folosit
     */
    public function redeemDiscount(User $user, string $code)
    {
        $userDiscounts = json_decode($user->used_discounts, true) ?? [];

        foreach ($userDiscounts as $key => $discount) {
            if ($discount['code'] === $code && !$discount['used']) {
                $userDiscounts[$key]['used'] = true;
                $user->used_discounts = json_encode($userDiscounts);
                $user->save();

                return response()->json([
                    'message' => "Ai folosit codul {$code} pentru {$discount['discount']}% reducere!",
                    'discount' => $discount['discount']
                ]);
            }
        }

        return response()->json(['error' => 'Cod invalid sau deja folosit'], 400);
    }
}
