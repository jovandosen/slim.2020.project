<?php

namespace App\Mvc\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
	protected $table = 'images';

	protected $fillable = ['user_id', 'gallery_id', 'name'];

	public function gallery()
	{
		return $this->belongsTo('App\Mvc\Models\Gallery');
	}
}