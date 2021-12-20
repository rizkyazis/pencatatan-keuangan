<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;

    protected $fillable = ['saldo'];

    public function history(){
        return $this->hasMany(History::class);
    }
}
