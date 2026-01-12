<?php

namespace App\Modules\User\Application\UseCases;

use App\Core\Telegram\Application\Handlers\Command\CommandHandlerDTO;
use App\Core\Telegram\Infrastructure\Http\DTOs\SendMessageDTO;
use App\Core\Telegram\Infrastructure\Http\DTOs\TelegramApiResponse;
use App\Core\Telegram\Infrastructure\Http\TelegramApiClient;
use App\Modules\User\Domain\Repository\UserRepositoryInterface;
use App\Modules\User\Infrastructure\Repository\UserRepository;
use Illuminate\Support\Facades\Log;

final readonly class CreateTelegramUserHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private TelegramApiClient $telegramApiClient
    )
    {
    }

    public function handle(CommandHandlerDTO $data): void
    {
        $this->userRepository->createUser($data->telegramId);

        $this->telegramApiClient->sendMessage(new SendMessageDTO(
            $data->telegramId,
            $data->message
        ));

        Log::debug('123');
    }
}
