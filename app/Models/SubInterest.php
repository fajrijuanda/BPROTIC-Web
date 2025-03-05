<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubInterest extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'interest_id'];

    public function interest()
    {
        return $this->belongsTo(Interest::class);
    }

    public function studentInterests()
    {
        return $this->hasMany(StudentInterest::class);
    }
}
