<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Hobby extends Authenticatable
{
    use Notifiable;
    protected $table = 'hobby';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    
    
}
