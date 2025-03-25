<?php

namespace App\Services;

use App\Events\UserEligibleForPromoCodeEvent;
use App\Jobs\SendMailPromoCodeGrantedJob;
use App\Models\Medal;
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
    public function assignPromoForMedalAndNotify(User $user, string $tier)
    {
        $medal = Medal::where('tier', $tier)->first();

        if (!$medal || !$medal->discount) {
            return;
        }

        $usedDiscounts = json_decode($user->used_discounts, true) ?? [];

        // Evită dublura
        if (isset($usedDiscounts[$tier])) {
            return;
        }

        $code = strtoupper(\Str::random(10));

        $usedDiscounts[$tier] = [
            'code' => $code,
            'discount' => $medal->discount,
            'used' => false,
        ];

        $user->used_discounts = json_encode($usedDiscounts);
        $user->save();

        // Trimitem email cu promo code și punctele câștigate
        dispatch(new SendMailPromoCodeGrantedJob($user, $code, $medal->discount, strtoupper($tier)));

        // Notificare despre noul discount
        broadcast(new UserEligibleForPromoCodeEvent($user, $medal->discount, $this->notificationService));

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
