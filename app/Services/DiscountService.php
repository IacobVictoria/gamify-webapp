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
        $usedDiscounts = json_decode($user->used_discounts, true) ?? [];

        $availableDiscounts = [];

        foreach ($usedDiscounts as $points => $discountData) {
            // Verificăm dacă discount-ul există în baza de date și dacă nu a fost folosit
            $availableDiscounts[] = [
                'discount' => $discountData['discount'],
                'used' => $discountData['used'],
                'code' => $discountData['used'] ? 'Utilizat' : $discountData['code']
            ];
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
    public function markPromoCodeAsUsed($user, $promoCode)
    {
        // Obține lista de discount-uri folosite de utilizator
        $usedDiscounts = json_decode($user->used_discounts, true) ?? [];

        // Parcurge discount-urile și caută promo code-ul
        foreach ($usedDiscounts as $key => &$discount) {
            if ($discount['code'] === $promoCode && !$discount['used']) {
                $discount['used'] = true; // Marcare ca folosit
            }
        }

        // Salvează noul JSON în baza de date
        $user->update([
            'used_discounts' => json_encode($usedDiscounts),
        ]);
    }

}
