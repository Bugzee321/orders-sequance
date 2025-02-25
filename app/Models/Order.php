<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'total',
        'status',
    ];
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->status = $order->total > 1000 ? 'pending_approval' : 'approved';
        });

        static::updating(function ($order) {
            if ($order->isDirty('status')) {
                $order->history()->create([
                    'old_status' => $order->getOriginal('status'),
                    'new_status' => $order->status,
                ]);
            }
        });
    }
    
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function history()
    {
        return $this->hasMany(OrderHistory::class);
    }

    public function needsApproval()
    {
        return $this->status === 'pending_approval';
    }
}
