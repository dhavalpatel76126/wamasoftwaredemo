<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Category extends Authenticatable
{
    use Notifiable;
    protected $table = 'category';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    

    
}
