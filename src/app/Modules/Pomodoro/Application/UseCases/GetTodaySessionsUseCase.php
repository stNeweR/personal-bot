<?php

namespace App\Modules\Pomodoro\Application\UseCases;

use App\Core\Telegram\Infrastructure\Services\Telegram\DTOs\SendMessageDTO;
use App\Core\Telegram\Infrastructure\Services\Telegram\TelegramApiClient;
use App\Modules\Pomodoro\Application\DTOs\GetTodaySessionsDTO;
use App\Modules\Pomodoro\Domain\Repository\PomodoroSessionsRepositoryInterface;
use App\Modules\User\Infrastructure\Models\User;

final readonly class GetTodaySessionsUseCase
{
    public function __construct(
        private PomodoroSessionsRepositoryInterface $pomodoroSessionsRepository
    ) {}

    public function execute(GetTodaySessionsDTO $data): void
    {
        $user = User::where('telegram_id', $data->telegramId)->first();

        if (! $user) {
            (new TelegramApiClient)->sendMessage(new SendMessageDTO(
                $data->telegramId,
                'Сначала авторизуйтесь в боте командой /start'
            ));

            return;
        }

        $todaySessions = $this->pomodoroSessionsRepository->getTodaySessions($user->id);

        if ($todaySessions->isEmpty()) {
            (new TelegramApiClient)->sendMessage(new SendMessageDTO(
                $data->telegramId,
                'У вас нет сессий за сегодняшний день.'
            ));

            return;
        }

        $table = "<b>Сессии за сегодня:</b>\n\n";
        $table .= '<pre>';
        $table .= "+---+---------------+---------------+------+----------------+\n";
        $table .= "| № | Время начала  | Статус        | Цикл | Время окончания|\n";
        $table .= "+---+---------------+---------------+------+----------------+\n";

        foreach ($todaySessions as $index => $session) {
            $startTime = $session->start_at->format('H:i:s');
            $status = $this->getStatusText($session->current_status->value);
            $cycle = $session->current_cycle;
            $endTime = $session->end_at ? $session->end_at->format('H:i:s') : '-';

            $num = str_pad($index + 1, 2, ' ', STR_PAD_BOTH);
            $startTimePad = str_pad($startTime, 14, ' ', STR_PAD_RIGHT);
            $statusPad = str_pad(mb_substr($status, 0, 14), 14, ' ', STR_PAD_RIGHT);
            $cyclePad = str_pad($cycle, 4, ' ', STR_PAD_BOTH);
            $endTimePad = str_pad($endTime, 15, ' ', STR_PAD_RIGHT);

            $table .= '| '.$num.' | '.$startTimePad.' | '.$statusPad.' | '.$cyclePad.' | '.$endTimePad." |\n";
        }

        $table .= "+---+---------------+---------------+------+----------------+\n";
        $table .= '</pre>';

        (new TelegramApiClient)->sendMessage(new SendMessageDTO(
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
