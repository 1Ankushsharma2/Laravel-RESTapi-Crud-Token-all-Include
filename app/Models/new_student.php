<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Import the Authenticatable class
use Laravel\Passport\HasApiTokens; // Import HasApiTokens trait

class new_student extends Authenticatable // Extend Authenticatable
{
    use HasFactory, HasApiTokens; // Use HasApiTokens trait

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
