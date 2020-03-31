<?php

namespace App\Mvc\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	protected $table = 'posts';

	protected $fillable = ['user_id', 'title', 'content', 'image'];

	public function user()
	{
		return $this->belongsTo('App\Mvc\Models\User');
	}
}