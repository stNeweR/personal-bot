<?php

namespace App\Core\Telegram\Application\Handlers\Command;

use App\Core\Telegram\Application\DTOs\TelegramUpdateDTO;
use App\Core\Telegram\Application\Strategies\Command\CommandStrategy;
use Illuminate\Support\Facades\Log;

final readonly class HandlerDispatcher
{
    public function __construct(
        private CommandStrategy $commandStrategy
    ) {}

    public function dispatch(TelegramUpdateDTO $data): void
    {
        $command = $data->getCommand();

        try {
            if ($command) {
                $this->commandStrategy->execute($command, $data);
            }

            //            if ($data->callbackData) {
            //                $this->handleCallbackQuery($data);
            //                return;
            //            }
            //
            //            if ($data->messageText) {
            //                $this->handleMessage($data);
            //                return;
            //            }
        } catch (\Exception $exception) {
            $this->handleError($data);
            Log::warning($exception->getMessage(), $data->toArray());
        }
    }

    private function handleError(TelegramUpdateDTO $data): void
    {
        Log::error('Error in handler', [
            'user_id' => $data->userId,
        ]);
    }
}
