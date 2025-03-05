<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;



    protected $fillable = ['finance_id', 'amount', 'category', 'reason', 'date'];

    public function finance()
    {
        return $this->belongsTo(Finance::class);
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
