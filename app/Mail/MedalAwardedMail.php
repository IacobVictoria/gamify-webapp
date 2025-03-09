<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MedalAwardedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $tier;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $tier)
    {
        $this->user = $user;
        $this->tier = ucfirst($tier); // Capitalizare medală
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('🏅 You have earned a new medal!')
            ->view('mail.medal_awarded')
            ->with([
                'user' => $this->user,
                'tier' => $this->tier
            ]);
    }
}
