<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    protected $fillable = ['name', 'country'];

    protected $attributes = [
        'budget' => 5000000.00,
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getBudget(): string
    {
        return (string) $this->budget;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getSumValue(): string
    {
        $this->load('players');
        /** @var Player $player */
        $sumValue = $this->players->sum(fn ($player) => (float) $player->getValue());

        return number_format($sumValue, 2, '.', '');
    }

    public function getCreatedAt(): ?Carbon
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->updated_at;
    }

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function transfersFrom(): HasMany
    {
        return $this->hasMany(Transfer::class, 'from_team_id');
    }

    public function transfersTo(): HasMany
    {
        return $this->hasMany(Transfer::class, 'to_team_id');
    }
}
