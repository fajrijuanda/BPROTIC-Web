<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function subInterests()
    {
        return $this->hasMany(SubInterest::class);
    }

    public function studentInterests()
    {
        return $this->hasMany(StudentInterest::class);
    }
}
