<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'finance_id', 'amount', 'source', 'date'];

    public function finance()
    {
        return $this->belongsTo(Finance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'related');
    }
    
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'related');
    }
}
