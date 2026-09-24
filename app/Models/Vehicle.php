<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;

    protected $fillable = ['plate_number', 'type', 'name'];

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    /** Label used in selects and reports, e.g. "ABC 123 - Hilux". */
    protected function label(): Attribute
    {
        return Attribute::get(fn() => trim($this->plate_number . ' ' . ($this->name ? '- ' . $this->name : '')));
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('plate_number', 'like', "%{$term}%")
                ->orWhere('name', 'like', "%{$term}%")
                ->orWhere('type', 'like', "%{$term}%");
        });
    }
}
