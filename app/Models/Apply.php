<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apply extends Model
{
    use HasFactory;
    protected $table ='applies';
    protected $fillable = [
        'company',
        'name',
        'email',
        'tel',
        'office_address',
        'factory_address',
        'explanation',
    ];
}
