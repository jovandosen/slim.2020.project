<?php

namespace App\Mvc\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
	protected $table = 'galleries';

	protected $fillable = ['user_id', 'name', 'description'];

	public function user()
	{
		return $this->belongsTo('App\Mvc\Models\User');
	}
}