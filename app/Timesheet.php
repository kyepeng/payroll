<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'user_id', 'date', 'hours_worked', 'description'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
