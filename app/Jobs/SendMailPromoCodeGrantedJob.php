<?php

namespace App\Jobs;

use App\Mail\PromoCodeGrantedMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
class SendMailPromoCodeGrantedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $promoCode;
    protected $discount;
    protected $tier;

    public function __construct(User $user, string $promoCode, int $discount, $tier)
    {
        $this->user = $user;
        $this->promoCode = $promoCode;
        $this->discount = $discount;
        $this->points = $tier;
    }

    public function handle()
    {
        Mail::to($this->user->email)->send(new PromoCodeGrantedMail(
            $this->user,
            $this->promoCode,
            $this->discount,
            $this->tier
        ));
    }
}
