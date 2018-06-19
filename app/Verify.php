<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Verify extends Model
{
    protected $guarded = [];

    protected $table = 'verifys';

	protected $dates = ['due_date'];


    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
