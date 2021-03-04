<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Userhobby extends Authenticatable
{
    use Notifiable;
    protected $table = 'user_hobby';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hobby_id', 'user_id',
    ];
    
    public function hobby()
    {
        return $this->belongsTo(Hobby::class,'hobby_id');
    }

}
