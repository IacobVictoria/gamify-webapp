<?php

namespace App\Jobs;

use App\Mail\MedalEmail;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Log;

class SendMedalEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $medal;
    /**
     * Create a new job instance.
     */
    public function __construct( User $user,  string $medal)
    { 
        $this->user = $user;
        $this->medal = $medal;
       // dd($this->user);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {//dd($this->user->email);
            // Mail::to($this->user->email)->send(new MedalEmail($this->user, $this->medal));
            $email = new MedalEmail($this->user, $this->medal);
            Mail::to($this->user->email)->send($email);
      
    }
}
