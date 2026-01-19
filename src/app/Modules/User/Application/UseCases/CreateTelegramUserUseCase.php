<?php

namespace App\Modules\User\Application\UseCases;

use App\Core\Telegram\Application\Handlers\Command\CommandHandlerDTO;
use App\Core\Telegram\Infrastructure\Services\Telegram\DTOs\SendMessageDTO;
use App\Core\Telegram\Infrastructure\Services\Telegram\TelegramApiClient;
use App\Modules\User\Domain\Contracts\TelegramAdapterInterface;
use App\Modules\User\Infrastructure\Adapters\TelegramAdapter;
use App\Modules\User\Infrastructure\Repository\UserRepository;
use Illuminate\Support\Facades\Log;

final readonly class CreateTelegramUserUseCase
{
    public function __construct(
        private UserRepository $userRepository,
        private TelegramAdapterInterface $telegramAdapter
    ) {}

    public function execute(CommandHandlerDTO $data): void
    {
        $this->userRepository->createUser($data->telegramId);

        $this->telegramAdapter->sendMessage(
            $data->telegramId,
            $data->message
        );

        Log::debug('123');
    }
}
