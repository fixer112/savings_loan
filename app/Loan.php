<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{

	protected $guarded = [];

	protected $dates = ['due_date', 'week_due_date'];


    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
