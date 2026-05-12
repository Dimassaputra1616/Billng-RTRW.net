<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use AlizHarb\ActivityLog\Contracts\HasActivityLogTitle;

class Customer extends Model implements HasActivityLogTitle
{
    use LogsActivity;

    public function getActivityLogTitle(): string
    {
        return $this->name ?? 'Pelanggan #' . $this->id;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Pelanggan')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function (string $eventName) {
                $modelName = 'Pelanggan';
                $name = $this->name ?? '';
                if ($eventName === 'created') {
                    return "Menambahkan {$modelName} baru: {$name}";
                }
                if ($eventName === 'updated') {
                    $changes = collect($this->getDirty())
                        ->keys()
                        ->map(fn ($key) => str_replace('_', ' ', $key))
                        ->implode(', ');
                    return "Mengubah {$modelName} {$name} (field: {$changes})";
                }
                if ($eventName === 'deleted') {
                    return "Menghapus {$modelName}: {$name}";
                }
                return "{$eventName} {$modelName}: {$name}";
            });
    }
    protected $fillable = [
        'name', 'phone_number', 'address', 'location_lat', 'location_lng',
        'pppoe_username', 'pppoe_password', 'static_ip',
        'internet_package_id', 'status', 'installation_date', 'billing_day'
    ];

    protected function casts(): array
    {
        return [
            'installation_date' => 'date',
            'location_lat' => 'decimal:8',
            'location_lng' => 'decimal:8',
        ];
    }

    public function internetPackage()
    {
        return $this->belongsTo(InternetPackage::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
