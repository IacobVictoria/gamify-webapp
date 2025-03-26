<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class PromoCodeGrantedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $promoCode;
    public $discount;
    public $tier;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $promoCode, int $discount, $tier)
    {
        $this->user = $user;
        $this->promoCode = $promoCode;
        $this->discount = $discount;
        $this->tier = $tier;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 You’ve earned a promo code for reaching ' . $this->tier . ' medal!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.promo_code',
        );
    }
}
