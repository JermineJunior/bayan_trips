<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TripType extends Model
{
    protected $fillable = ['name', 'is_default'];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        // The default type can never be deleted (returning false cancels the delete).
        static::deleting(fn (TripType $type) => ! $type->is_default);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    public static function default(): ?self
    {
        return static::where('is_default', true)->first();
    }
}
