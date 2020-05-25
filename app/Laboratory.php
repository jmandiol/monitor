<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'external', 'commune_id'
    ];

    public function users() {
        return $this->hasMany('App\User');
    }
}
