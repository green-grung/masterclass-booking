<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'craft_id', 'master_id', 'title', 'description',
        'date', 'time_slot', 'max_participants', 'price',
    ];

    protected $casts = [
        'date' => 'date',
        'price' => 'decimal:2',
    ];

    public function craft(): BelongsTo
    {
        return $this->belongsTo(Craft::class);
    }

    public function master(): BelongsTo
    {
        return $this->belongsTo(User::class, 'master_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function availablePlaces(): int
    {
        $booked = $this->registrations()->where('status', 'confirmed')->count();

        return max(0, $this->max_participants - $booked);
    }

    public function isUserRegistered(int $userId): bool
    {
        return $this->registrations()
            ->where('user_id', $userId)
            ->where('status', 'confirmed')
            ->exists();
    }
}
