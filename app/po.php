<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class po extends Model {

	//
	protected $fillable = ['id', 'po', 'status', 'created_at', 'updated_at'];
	protected $table = 'pos';

}
