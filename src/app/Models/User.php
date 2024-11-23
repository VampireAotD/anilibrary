<?php

declare(strict_types=1);

namespace App\Models;

use App\Notifications\Auth\VerifyEmailNotification;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasUuids;
    use HasApiTokens;
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use HasRoles;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * @return HasOne<TelegramUser, $this>
     */
    public function telegramUser(): HasOne
    {
        return $this->hasOne(TelegramUser::class);
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification());
    }

    /**
     * @psalm-suppress TooManyTemplateParams Suppressed because PHPStan needs description, but Psalm conflicts with it
     * @return Attribute<bool, never>
     */
    protected function hasTemporaryEmail(): Attribute
    {
        return Attribute::make(
            get: fn(): bool => str_ends_with($this->email, config('mail.temporary_domain')),
        )->shouldCache();
    }
}
