<?php

namespace App\Mvc\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $table = 'comments';

	protected $fillable = ['post_id', 'user_id', 'comment'];

	public function post()
	{
		return $this->belongsTo('App\Mvc\Models\Post');
	}

	public function user()
	{
		return $this->belongsTo('App\Mvc\Models\User');
	}
}