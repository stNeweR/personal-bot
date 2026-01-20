<?php

namespace App\Modules\User\Infrastructure\Models;

use App\Modules\User\Domain\Enums\UserStateValue;
use Database\Factories\UserStateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @class UserState
 *
 * @property int $user_id
 * @property UserStateValue $state_value
 */
final class UserState extends Model
{
    /** @use HasFactory<UserStateFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'state_value',
    ];

    protected $casts = [
        'state_value' => UserStateValue::class,
    ];

    protected static function newFactory(): UserStateFactory
    {
        return UserStateFactory::new();
    }
}
