<?php

namespace App\Jobs;

use App\Mail\InvoiceIssuedMail;
use App\Models\ClientOrder;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $order;
    protected $invoiceUrl;

    public function __construct(User $user, ClientOrder $order, string $invoiceUrl)
    {
        $this->user = $user;
        $this->order = $order;
        $this->invoiceUrl = $invoiceUrl;
    }

    public function handle()
    {
        Mail::to($this->user->email)->send(new InvoiceIssuedMail($this->user, $this->order, $this->invoiceUrl));
    }
}
