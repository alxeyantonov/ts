<?php

namespace Tests\Feature\Api;

use App\Models\Email;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmailsControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testIndexAllEmails()
    {
        Email::factory(5)->create();

        $this->getJson('/api/emails')
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure(['data' => ['*' => $this->getEmailResponseStructure()]]);
    }

    public function testIndexEmailsWithFilterByUser()
    {
        $user = User::factory()->withEmails(5)->create();
        User::factory()->withEmails(5)->create();

        $response = $this->getJson('/api/emails?userId='.$user->id)
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure(['data' => ['*' => $this->getEmailResponseStructure()]]);

        $emails = collect($response->json('data'));
        $emails->each(function ($email) use ($user) {
            $this->assertEquals($user->id, $email['userId']);
        });
    }

    public function testCreateEmailForUser(): void
    {
        $user = User::factory()->create();

        $email = $this->faker->email();
        $data = [
            'userId' => $user->id,
            'email' => $email,
        ];

        $this->postJson('/api/emails', $data)
            ->assertCreated()
            ->assertJsonFragment(['email' => $email])
            ->assertJsonStructure($this->getEmailResponseStructure());

        $this->assertDatabaseHas(Email::class, [
            'user_id' => $user->id,
            'email' => $email,
        ]);
    }

    public function testCreateErrorEmailWithDuplicateName()
    {
        $user = User::factory()->withEmails()->create();

        $data = [
            'userId' => $user->id,
            'email' => $user->emails->first()->email,
        ];

        $this->postJson('/api/emails', $data)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function testCreateErrorEmailWithInvalidParams()
    {
        $this->postJson('/api/emails')
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'userId']);
    }

    public function testShowEmail(): void
    {
        $email = Email::factory()->create();

        $this->getJson("/api/emails/{$email->id}")
            ->assertOk()
            ->assertJsonFragment(['email' => $email->email])
            ->assertJsonStructure($this->getEmailResponseStructure());
    }

    public function testUpdateEmail(): void
    {
        $email = Email::factory()->create();
        $newEmail = $this->faker->email();

        $data = ['email' => $newEmail];

        $this->putJson("/api/emails/{$email->id}", $data)
            ->assertOk()
            ->assertJsonFragment(['email' => $newEmail])
            ->assertJsonStructure($this->getEmailResponseStructure());

        $this->assertDatabaseHas(Email::class, [
            'id' => $email->id,
            'email' => $newEmail,
        ]);
    }

    public function testUpdateErrorEmailWithDuplicateName()
    {
        $user = User::factory()->withEmails(2)->create();

        $first = $user->emails->pop();
        $second = $user->emails->pop();

        $data = [
            'email' => $first->email,
        ];

        $this->putJson("/api/emails/{$second->id}", $data)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function testUpdateErrorEmailWithInvalidEmailFormat()
    {
        $email = Email::factory()->create();

        $data = [
            'email' => $this->faker->word(),
        ];

        $this->putJson("/api/emails/{$email->id}", $data)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function testDeleteEmail(): void
    {
        $email = Email::factory()->create();

        $this->deleteJson("/api/emails/{$email->id}")->assertNoContent();

        $this->assertDatabaseMissing(Email::class, [
            'id' => $email->id,
        ]);
    }

    private function getEmailResponseStructure(): array
    {
        return [
            'id',
            'userId',
            'email',
            'createdAt',
        ];
    }
}
