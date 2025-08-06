<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class Email extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'user_id',
    ];

    public function scopeByUserId(Builder $builder, ?int $userId): void
    {
        $builder->when($userId, fn(Builder $builder) => $builder->where('user_id', $userId));
    }

    public function routeNotificationForMail(Notification $notification): string
    {
        return $this->email;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
