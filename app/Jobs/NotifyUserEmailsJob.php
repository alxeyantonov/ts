<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\GreetingNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;

class NotifyUserEmailsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly User $user
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->user
            ->emails()
            ->chunkById(1000, function (Collection $emails) {
                foreach ($emails as $email) {
                    $email->notify(new GreetingNotification());
                }
            });
    }
}
