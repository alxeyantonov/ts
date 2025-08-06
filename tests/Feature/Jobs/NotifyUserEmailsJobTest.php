<?php

namespace Tests\Feature\Jobs;

use App\Jobs\NotifyUserEmailsJob;
use App\Models\User;
use App\Notifications\GreetingNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifyUserEmailsJobTest extends TestCase
{
    use RefreshDatabase;

    public function testHandleSendNotificationToAllUserEmails(): void
    {
        Notification::fake();

        $user = User::factory()->withEmails(5)->create();

        $job = new NotifyUserEmailsJob($user);
        $job->handle();

        foreach ($user->emails as $email) {
            Notification::assertSentTo(
                $email,
                GreetingNotification::class
            );
        }

        Notification::assertSentTimes(GreetingNotification::class, 5);
    }
}
