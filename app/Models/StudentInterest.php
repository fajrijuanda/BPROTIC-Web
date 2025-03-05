<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentInterest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'interest_id', 'sub_interest_id', 'reason'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function interest()
    {
        return $this->belongsTo(Interest::class);
    }

    public function subInterest()
    {
        return $this->belongsTo(SubInterest::class);
    }
}
