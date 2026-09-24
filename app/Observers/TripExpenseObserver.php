<?php

namespace App\Observers;

use App\Models\TripExpense;

class TripExpenseObserver
{
     public function saved(TripExpense $expense): void
    {
        $expense->trip?->recalculate();
    }

    public function deleted(TripExpense $expense): void
    {
        $expense->trip?->recalculate();
    }
}
