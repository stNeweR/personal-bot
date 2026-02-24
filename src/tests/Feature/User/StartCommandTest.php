<?php

namespace Tests\Feature\User;

use App\Modules\User\Infrastructure\Models\User;
use Tests\Assertions\TelegramAssertion;
use Tests\SetUps\SetupTelegram;
use Tests\TestCase;

final class StartCommandTest extends TestCase
{
    use SetupTelegram, TelegramAssertion;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setupTelegramApi();
    }

    public function test_send_first_command(): void
    {
        $telegramId = 123456789;

        $response = $this->postJson($this->telegramWebhookUrl, [
            'update_id' => 1001,
            'message' => [
                'message_id' => 1,
                'from' => [
                    'id' => $telegramId,
                    'is_bot' => false,
                    'first_name' => 'Test',
                    'last_name' => 'User',
                    'username' => 'testuser',
                    'language_code' => 'ru',
                ],
                'chat' => [
                    'id' => $telegramId,
                    'type' => 'private',
                    'first_name' => 'Test',
                    'last_name' => 'User',
                    'username' => 'testuser',
                ],
                'date' => time(),
                'text' => '/start',
                'entities' => [
                    [
                        'type' => 'bot_command',
                        'offset' => 0,
                        'length' => 6,
                    ],
                ],
            ],
        ]);

        $response->assertOk();

        $this->assertTelegramMessageContains(__('user.welcome'));
        $this->assertTelegramMessageSent($telegramId, __('user.welcome'));
        $this->assertDatabaseHas(User::class, [
            'telegram_id' => $telegramId,
        ]);
    }

    public function test_send_more_command(): void
    {
        $telegramId = 123456789;

        User::factory()->createOne([
            'telegram_id' => $telegramId,
        ]);

        $response = $this->postJson($this->telegramWebhookUrl, [
            'update_id' => 1001,
            'message' => [
                'message_id' => 1,
                'from' => [
                    'id' => $telegramId,
                    'is_bot' => false,
                    'first_name' => 'Test',
                    'last_name' => 'User',
                    'username' => 'testuser',
                    'language_code' => 'ru',
                ],
                'chat' => [
                    'id' => $telegramId,
                    'type' => 'private',
                    'first_name' => 'Test',
                    'last_name' => 'User',
                    'username' => 'testuser',
                ],
                'date' => time(),
                'text' => '/start',
                'entities' => [
                    [
                        'type' => 'bot_command',
                        'offset' => 0,
                        'length' => 6,
                    ],
                ],
            ],
        ]);

        $response->assertOk();

        $this->assertTelegramMessageContains(__('user.already_registered'));
        $this->assertTelegramMessageSent($telegramId, __('user.already_registered'));
        $this->assertDatabaseHas(User::class, [
            'telegram_id' => $telegramId,
        ]);
    }
}
