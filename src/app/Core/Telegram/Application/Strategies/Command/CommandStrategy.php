<?php

namespace App\Core\Telegram\Application\Strategies\Command;

use;
use App\Core\Telegram\Application\DTOs\TelegramUpdateDTO;
use App\Core\Telegram\Application\Handlers\Command\CommandHandlerDTO;
use App\Core\Telegram\Application\Handlers\Command\CommandHandlerInterface;
use App\Core\Telegram\Exceptions\UnknownCommandException;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Log;

final class CommandStrategy
{
    /** @var array<string, class-string<CommandHandlerInterface>> */
    private array $commandsHandler = [];

    public function __construct(
        private readonly Container $container
    ) {
        $this->commandsHandler = config('telegram.commands_handler', []);
    }

    public function execute(string $command, TelegramUpdateDTO $data): void
    {
        try {
            $handlerClass = $this->commandsHandler[$command] ?? null;

            if (is_null($handlerClass)) {
                throw new UnknownCommandException($command, $data->userId);
            }

            /** @var CommandHandlerInterface $handler */
            $handler = $this->container->make($handlerClass);

            if (! $handler instanceof CommandHandlerInterface) {
                throw new \RuntimeException(
                    sprintf('Handler %s must implement CommandHandlerInterface', $handlerClass)
                );
            }

            $handler->handle(new CommandHandlerDTO(
                $data->updateId,
                $data->userId,
                $data->messageText
            ));
        } catch (\Exception $e) {
            Log::error('Failed to create command handler', [
                'handler' => $handlerClass,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
