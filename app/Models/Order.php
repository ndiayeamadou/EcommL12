<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'tracking_no',
        'fullname',
        'phone',
        'email',
        'postal_code',
        'address',
        'status_message',
        'status_note',
        'payment_mode',
        'payment_id',
        'agent_id',
        'back',
        'updated_by'
    ];

    public function user () {
        return $this->belongsTo(User::class);
    }

    public function agent () {
        return $this->belongsTo(User::class, 'agent_id');
    }

    //public function agent_de_suivi () {
    public function updatedBy () {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get all of the orderItems for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

}
