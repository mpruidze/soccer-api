<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PlayerPosition;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Player extends Model
{
    /** @use HasFactory<\Database\Factories\PlayerFactory> */
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'country', 'value'];

    protected $attributes = [
        'value' => 1000000.00,
    ];

    protected $casts = [
        'position' => PlayerPosition::class,
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getValue(): string
    {
        return (string) $this->value;
    }

    public function getTranslatedPosition(): string
    {
        return $this->position->translate();
    }

    public function getTeamId(): int
    {
        return $this->team_id;
    }

    public function getCreatedAt(): ?Carbon
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->updated_at;
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function transfers(): HasMany
    {
        return $this->hasMany(Transfer::class);
    }

    public function latestActiveTransfer(): HasOne
    {
        return $this->hasOne(Transfer::class)->ofMany([], function (Builder $query) {
            $query->where('is_transferred', false);
        });
    }
}
