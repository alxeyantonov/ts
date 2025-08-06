<?php

namespace Feature\Api;

use App\Jobs\NotifyUserEmailsJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSendUserEmails(): void
    {
        Queue::fake();

        $user = User::factory()->create();

        $this->postJson("/api/users/{$user->id}/send-emails")->assertNoContent();

        Queue::assertPushed(NotifyUserEmailsJob::class, function ($job) use ($user) {
            return $job->user->is($user);
        });
    }
}
