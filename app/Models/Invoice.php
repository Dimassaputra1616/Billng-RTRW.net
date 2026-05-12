<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use AlizHarb\ActivityLog\Contracts\HasActivityLogTitle;

class Invoice extends Model implements HasActivityLogTitle
{
    use LogsActivity;

    public function getActivityLogTitle(): string
    {
        return $this->invoice_number ?? 'Tagihan #' . $this->id;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Tagihan')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function (string $eventName) {
                $modelName = 'Tagihan';
                $invoiceNumber = $this->invoice_number ?? '';
                if ($eventName === 'created') {
                    return "Membuat {$modelName} baru: {$invoiceNumber}";
                }
                if ($eventName === 'updated') {
                    $changes = collect($this->getDirty())
                        ->keys()
                        ->map(fn ($key) => str_replace('_', ' ', $key))
                        ->implode(', ');
                    return "Mengubah {$modelName} {$invoiceNumber} (field: {$changes})";
                }
                if ($eventName === 'deleted') {
                    return "Menghapus {$modelName}: {$invoiceNumber}";
                }
                return "{$eventName} {$modelName}: {$invoiceNumber}";
            });
    }
    protected $fillable = [
        'customer_id', 'invoice_number', 'period_month', 'period_year',
        'amount', 'status', 'due_date'
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'amount' => 'decimal:2',
            'period_month' => 'integer',
            'period_year' => 'integer',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
