<?php

namespace App\Core\Telegram\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class TelegramWebhookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'update_id' => ['integer', 'required'],
            'message' => ['array'],
            'from' => ['array'],
            'chat' => ['array'],
            'date' => ['integer'],
            'text' => ['string'],
            'entities' => ['array'],
        ];
    }
}
