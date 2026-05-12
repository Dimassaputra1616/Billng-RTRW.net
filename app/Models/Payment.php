<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use AlizHarb\ActivityLog\Contracts\HasActivityLogTitle;

class Payment extends Model implements HasActivityLogTitle
{
    use LogsActivity;

    public function getActivityLogTitle(): string
    {
        return 'Pembayaran #' . $this->id;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Pembayaran')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function (string $eventName) {
                $modelName = 'Pembayaran';
                $id = $this->id ?? '';
                if ($eventName === 'created') {
                    return "Mencatat {$modelName} baru #{$id} (Rp " . number_format($this->amount_paid ?? 0, 0, ',', '.') . ")";
                }
                if ($eventName === 'updated') {
                    $changes = collect($this->getDirty())
                        ->keys()
                        ->map(fn ($key) => str_replace('_', ' ', $key))
                        ->implode(', ');
                    return "Mengubah {$modelName} #{$id} (field: {$changes})";
                }
                if ($eventName === 'deleted') {
                    return "Menghapus {$modelName} #{$id}";
                }
                return "{$eventName} {$modelName} #{$id}";
            });
    }
    protected $fillable = [
        'customer_id', 'invoice_id', 'payment_method', 'amount_paid', 'payment_date', 'attachment_path'
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'datetime',
            'amount_paid' => 'decimal:2',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($payment) {
            if (!$payment->customer_id && $payment->invoice_id) {
                $payment->customer_id = $payment->invoice->customer_id;
            }
        });
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
