<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
    use HasFactory;
    protected $table ='checks';
    protected $fillable = [
        'name',
        'email',
        'company',
        'position',
        'country',
        'question',
        'used_in',
        'image',
    ];
}
