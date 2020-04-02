<?php

namespace App\Mvc\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	protected $table = 'users';

	protected $fillable = ['firstName', 'lastName', 'email', 'password'];

	public function posts()
	{
		return $this->hasMany('App\Mvc\Models\Post');
	}

	public function comments()
	{
		return $this->hasMany('App\Mvc\Models\Comment');
	}
}