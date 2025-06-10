<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function should_throw_validation_error_on_register(): void
    {
        $response = $this->postJson(route('register'));

        $response->assertInvalid(['name', 'email', 'password']);
    }

    #[Test]
    public function should_register(): void
    {
        Event::fake();

        $response = $this->postJson(route('register', [
            'name' => 'john',
            'email' => 'john@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]));

        $response->assertCreated()
            ->assertJsonStructure(['data' => ['user', 'token']]);
        $this->assertDatabaseHas('users', [
            'email' => 'john@gmail.com',
        ])
            ->assertDatabaseCount('users', 1);
        Event::assertDispatched(Registered::class);
    }

    #[Test]
    public function should_throw_validation_error_on_login(): void
    {
        $response = $this->postJson(route('login'));

        $response->assertInvalid(['email', 'password']);
    }

    #[Test]
    public function should_throw_error_on_wrong_credentials(): void
    {
        $this->createUsers([
            'email' => 'random@email.com',
            'password' => 'random_password',
        ]);

        $response = $this->postJson(route('login', [
            'email' => 'random@email.com',
            'password' => 'random',
        ]));

        $response->assertInvalid(['email'])
            ->assertJson(['message' => __('auth.failed')]);
    }

    #[Test]
    public function should_login(): void
    {
        $this->createUsers([
            'email' => 'random@email.com',
            'password' => 'random_password',
        ]);

        $response = $this->postJson(route('login', [
            'email' => 'random@email.com',
            'password' => 'random_password',
        ]));

        $response->assertOk()
            ->assertJsonStructure(['data' => ['user', 'token']]);
    }

    #[Test]
    public function should_logout(): void
    {
        $this->createAndAuthenticateUser();

        $response = $this->postJson(route('logout'));

        $response->assertOk();
    }

    #[Test]
    public function should_show_authenticated_user(): void
    {
        $this->createAndAuthenticateUser();

        $response = $this->getJson(route('auth.user'));

        $response->assertOk()
            ->assertJsonStructure($this->userStructure());
    }
}
