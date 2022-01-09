<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class mattress_phases extends Model {

	//
	protected $fillable = ['id', 'mattress_id', 'mattress', 'status', 'location','device','active','operator1','operator2','date','id_status'];
	protected $table = 'mattress_phases';

}
