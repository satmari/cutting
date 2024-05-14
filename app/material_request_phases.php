<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class material_request_phases extends Model {

	//
	protected $fillable = ['id', 'material_request_id', 'status', 'location', 'device','active','operator1','operator2','id_status'];
	protected $table = 'material_request_phases';

}
