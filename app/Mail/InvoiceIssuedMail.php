<?php

namespace App\Mail;

use App\Models\ClientOrder;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceIssuedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public ClientOrder $order;
    public string $invoiceUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, ClientOrder $order, string $invoiceUrl)
    {
        $this->user = $user;
        $this->order = $order;
        $this->invoiceUrl = $invoiceUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('🎉Factura ta este gata pentru descărcare!')
                    ->view('mail.invoice_issued_user')
                    ->with([
                        'user' => $this->user,
                        'order' => $this->order,
                        'invoiceUrl' => $this->invoiceUrl,
                    ]);
    }
}
