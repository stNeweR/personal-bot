<?php

namespace App\Modules\Pomodoro\Application\Jobs;

use App\Core\Telegram\Infrastructure\Services\Telegram\DTOs\SendMessageDTO;
use App\Core\Telegram\Infrastructure\Services\Telegram\TelegramApiClient;
use App\Modules\Pomodoro\Domain\Enums\PomodoroStatusValue;
use App\Modules\Pomodoro\Infrastructure\Models\PomodoroSession;
use App\Modules\Pomodoro\Infrastructure\Models\PomodoroSettings;
use App\Modules\User\Infrastructure\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPomodoroStageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private PomodoroSession $session,
        private User $user,
        private int $currentCycle = 1,
        private PomodoroStatusValue $currentStatus = PomodoroStatusValue::WORK
    ) {}

    public function handle(TelegramApiClient $telegramApi): void
    {
        $settings = PomodoroSettings::where('user_id', $this->user->id)->first();

        if (! $settings) {
            Log::info('test');
            $telegramApi->sendMessage(new SendMessageDTO(
                chatId: $this->user->telegram_id,
                text: 'Сначала установите настройки Pomodoro с помощью команды /addpomosettings'
            ));

            return;
        }

        $totalCycles = $settings->repeats_count ?? 1;

        $this->session->refresh();
        if ($this->session->current_status === PomodoroStatusValue::PAUSED ||
            $this->session->current_status === PomodoroStatusValue::FINISHED) {
            return;
        }

        if ($this->currentStatus === PomodoroStatusValue::WORK) {
            Log::info('work');
            $this->updateSessionStatus($this->session, PomodoroStatusValue::WORK);
            $telegramApi->sendMessage(new SendMessageDTO(
                chatId: $this->user->telegram_id,
                text: "Пора работать! Работайте в течение {$settings->work_duration} минут."
            ));

            $delay = now()->addMinutes($settings->work_duration);

            if ($this->currentCycle >= $totalCycles) {
                $delay = now()->addMinutes($settings->work_duration);
                ProcessPomodoroStageJob::dispatch(
                    $this->session,
                    $this->user,
                    $this->currentCycle,
                    PomodoroStatusValue::FINISHED
                )->delay($delay);
            } else {
                ProcessPomodoroStageJob::dispatch(
                    $this->session,
                    $this->user,
                    $this->currentCycle,
                    PomodoroStatusValue::BREAK
                )->delay($delay);
            }
        } elseif ($this->currentStatus === PomodoroStatusValue::FINISHED) {
            $this->finishSession($telegramApi);
        } else {
            if ($this->currentCycle % $settings->cycles_before_long_break === 0 && $this->currentCycle !== $totalCycles) {
                Log::info('long_break');
                $this->updateSessionStatus($this->session, PomodoroStatusValue::LONG_BREAK);
                $telegramApi->sendMessage(new SendMessageDTO(
                    chatId: $this->user->telegram_id,
                    text: "Длинный перерыв после {$this->currentCycle} цикла. Отдохните {$settings->long_break_duration} минут."
                ));

                $delay = now()->addMinutes($settings->long_break_duration);

                ProcessPomodoroStageJob::dispatch(
                    $this->session,
                    $this->user,
                    $this->currentCycle + 1,
                    PomodoroStatusValue::WORK
                )->delay($delay);
            } else {
                Log::info('break');
                $this->updateSessionStatus($this->session, PomodoroStatusValue::BREAK);

                $telegramApi->sendMessage(new SendMessageDTO(
                    chatId: $this->user->telegram_id,
                    text: "Короткий перерыв. Отдохните {$settings->break_duration} минут."
                ));

                $delay = now()->addMinutes($settings->break_duration);

                ProcessPomodoroStageJob::dispatch(
                    $this->session,
                    $this->user,
                    $this->currentCycle + 1,
                    PomodoroStatusValue::WORK
                )->delay($delay);
            }
        }
    }

    private function updateSessionStatus(PomodoroSession $session, PomodoroStatusValue $status): void
    {
        $session->update([
            'current_status' => $status,
            'current_cycle' => $this->currentCycle,
        ]);
    }

    private function finishSession(TelegramApiClient $telegramApi): void
    {
        $this->session->update([
            'current_status' => PomodoroStatusValue::FINISHED,
            'end_at' => now()
        ]);
        Log::info('finish');
        $telegramApi->sendMessage(new SendMessageDTO(
            chatId: $this->user->telegram_id,
            text: 'Поздравляем! Вы завершили все циклы Pomodoro.'
        ));
    }
}
