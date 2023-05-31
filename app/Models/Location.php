<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $table ='locations';
    protected $fillable = [
        'title',
        'address',
        'po',
        'director',
        'tel',
        'email',
        'scope',
        'categories',
    ];
}
