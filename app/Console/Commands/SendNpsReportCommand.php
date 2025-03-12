<?php

namespace App\Console\Commands;

use App\Services\DiscordNotificationService;
use Illuminate\Console\Command;

class SendNpsReportCommand extends Command
{

    protected $signature = 'send:nps-report';

    protected $description = 'Generates and sends the monthly NPS report to Discord.';

    protected $discordNotificationService;

    public function __construct(DiscordNotificationService $discordNotificationService)
    {
        parent::__construct();
        $this->discordNotificationService = $discordNotificationService;
    }


    public function handle()
    {
        $this->discordNotificationService->sendNpsReportToDiscord();
    }
}
