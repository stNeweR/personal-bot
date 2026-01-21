<?php

namespace App\Modules\Pomodoro\Application\Handlers\Command;

use App\Core\Telegram\Application\Handlers\Command\CommandHandlerDTO;
use App\Core\Telegram\Application\Handlers\Command\CommandHandlerInterface;
use App\Core\Telegram\Infrastructure\Services\Telegram\DTOs\SendMessageDTO;
use App\Core\Telegram\Infrastructure\Services\Telegram\TelegramApiClient;
use App\Modules\Pomodoro\Infrastructure\Models\PomodoroSession;
use App\Modules\User\Infrastructure\Models\User;
use Carbon\Carbon;

final class GetTodaySessionsHandler implements CommandHandlerInterface
{
    public function handle(CommandHandlerDTO $data): void
    {
        $user = User::where('telegram_id', $data->telegramId)->first();

        if (!$user) {
            (new TelegramApiClient())->sendMessage(new SendMessageDTO(
                $data->telegramId,
                'Сначала авторизуйтесь в боте командой /start'
            ));
            return;
        }

        $todaySessions = PomodoroSession::where('user_id', $user->id)
            ->whereDate('start_at', Carbon::today())
            ->orderBy('start_at', 'asc')
            ->get();

        if ($todaySessions->isEmpty()) {
            (new TelegramApiClient())->sendMessage(new SendMessageDTO(
                $data->telegramId,
                'У вас нет сессий за сегодняшний день.'
            ));
            return;
        }

        $table = "<b>Сессии за сегодня:</b>\n\n";
        $table .= "<pre>";
        $table .= "+---+---------------+---------------+------+----------------+\n";
        $table .= "| № | Время начала  | Статус        | Цикл | Время окончания|\n";
        $table .= "+---+---------------+---------------+------+----------------+\n";

        foreach ($todaySessions as $index => $session) {
            $startTime = $session->start_at->format('H:i:s');
            $status = $this->getStatusText($session->current_status->value);
            $cycle = $session->current_cycle;
            $endTime = $session->end_at ? $session->end_at->format('H:i:s') : '-';
            
            // Формируем строку с фиксированной шириной для каждого поля
            // Используем ручное форматирование для лучшей совместимости с кириллицей в Telegram
            $num = str_pad($index + 1, 2, ' ', STR_PAD_BOTH);
            $startTimePad = str_pad($startTime, 14, ' ', STR_PAD_RIGHT);
            $statusPad = str_pad(mb_substr($status, 0, 14), 14, ' ', STR_PAD_RIGHT);
            $cyclePad = str_pad($cycle, 4, ' ', STR_PAD_BOTH);
            $endTimePad = str_pad($endTime, 15, ' ', STR_PAD_RIGHT);
            
            $table .= "| " . $num . " | " . $startTimePad . " | " . $statusPad . " | " . $cyclePad . " | " . $endTimePad . " |\n";
        }
        
        $table .= "+---+---------------+---------------+------+----------------+\n";
        $table .= "</pre>";

        (new TelegramApiClient())->sendMessage(new SendMessageDTO(
            $data->telegramId,
            $table
        ));
    }

    private function getStatusText(string $status): string
    {
        $statusMap = [
            'paused' => 'Пауза',
            'finished' => 'Завершено',
            'work' => 'Работа',
            'break' => 'Перерыв',
            'long_break' => 'Длинный перерыв',
        ];

        return $statusMap[$status] ?? $status;
    }
}