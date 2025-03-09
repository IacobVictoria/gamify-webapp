<?php

namespace App\Jobs;

use App\Mail\MedalAwardedMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMedalAwardedMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $user;
    protected string $tier;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, string $tier)
    {
        $this->user = $user;
        $this->tier = $tier;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Mail::to($this->user->email)->send(new MedalAwardedMail($this->user, $this->tier));
    }
}
