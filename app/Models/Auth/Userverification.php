<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class Userverification extends Model
{
	   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [ 'token',
    ];

    public $timestamps = false;



    public function user()
    {

    return $this->belongsTo('App\Models\Auth\User', 'user_id');
    }
}
