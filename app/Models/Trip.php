<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use SoftDeletes;

    // Derived amounts are intentionally NOT fillable: only recalculate() sets them.
    protected $fillable = [
        'trip_type_id', 'customer_id', 'vehicle_id', 'driver_id',
        'from_location', 'to_location', 'trip_date',
        'price', 'driver_percentage', 'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'trip_date' => 'date',
            'price' => 'decimal:2',
            'driver_percentage' => 'decimal:2',
            'total_expenses' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'driver_amount' => 'decimal:2',
            'company_amount' => 'decimal:2',
        ];
    }

    // withTrashed so historical trips keep showing names of soft-deleted records (BR-5)
    public function tripType(): BelongsTo
    {
        return $this->belongsTo(TripType::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class)->withTrashed();
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class)->withTrashed();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(TripExpense::class);
    }

    /**
     * total_expenses = sum(expenses)
     * net            = price - total_expenses          (can be negative)
     * driver_amount  = net * driver_percentage / 100   (NOT clamped)
     * company_amount = net - driver_amount
     */
    public function recalculate(): void
    {
        $totalExpenses = round((float) $this->expenses()->sum('amount'), 2);
        $net = round((float) $this->price - $totalExpenses, 2);
        $driverAmount = round($net * (float) $this->driver_percentage / 100, 2);

        $this->forceFill([
            'total_expenses' => $totalExpenses,
            'net_amount' => $net,
            'driver_amount' => $driverAmount,
            'company_amount' => round($net - $driverAmount, 2),
        ])->saveQuietly();
    }

    public function scopeFilter(Builder $query, array $f): Builder
    {
        return $query
            ->when($f['date_from'] ?? null, fn ($q, $v) => $q->whereDate('trip_date', '>=', $v))
            ->when($f['date_to'] ?? null, fn ($q, $v) => $q->whereDate('trip_date', '<=', $v))
            ->when($f['vehicle_id'] ?? null, fn ($q, $v) => $q->where('vehicle_id', $v))
            ->when($f['driver_id'] ?? null, fn ($q, $v) => $q->where('driver_id', $v))
            ->when($f['customer_id'] ?? null, fn ($q, $v) => $q->where('customer_id', $v))
            ->when($f['trip_type_id'] ?? null, fn ($q, $v) => $q->where('trip_type_id', $v))
            ->when($f['from'] ?? null, fn ($q, $v) => $q->where('from_location', 'like', "%{$v}%"))
            ->when($f['to'] ?? null, fn ($q, $v) => $q->where('to_location', 'like', "%{$v}%"))
            ->when($f['q'] ?? null, fn ($q, $v) => $q->where(function (Builder $w) use ($v) {
                $w->where('from_location', 'like', "%{$v}%")
                    ->orWhere('to_location', 'like', "%{$v}%")
                    ->orWhere('notes', 'like', "%{$v}%");
            }));
    }
}
