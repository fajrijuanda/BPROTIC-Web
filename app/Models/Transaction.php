<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['finance_id', 'amount', 'related_id', 'related_type', 'previous_net_worth', 'current_net_worth', 'date'];

    public function finance()
    {
        return $this->belongsTo(Finance::class);
    }

    public function related()
    {
        return $this->morphTo();
    }
    
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'related');
    }
}
