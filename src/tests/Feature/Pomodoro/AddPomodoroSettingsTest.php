<?php

namespace Tests\Feature\Pomodoro;

use App\Modules\User\Domain\Models\User;
use Illuminate\Support\Facades\Http;
use Tests\SetUps\SetupTelegram;
use Tests\TestCase;

class AddPomodoroSettingsTest extends TestCase
{
    use SetupTelegram;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupTelegramApi();
    }

    public function test_command(): void
    {
        User::factory()->create([
            'telegram_id' => 123456789,
        ]);

        $request = [
            'update_id' => 987654321,
            'message' => [
                'message_id' => 101,
                'from' => [
                    'id' => 123456789,
                    'is_bot' => false,
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'username' => 'johndoe',
                    'language_code' => 'en',
                ],
                'chat' => [
                    'id' => 123456789,
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'username' => 'johndoe',
                    'type' => 'private',
                ],
                'date' => 1678886400,
                'text' => '/addpomosettings',
            ],
        ];

        $result = Http::post($this->telegramWebhookUrl, $request);

        dd($result);
    }
}
