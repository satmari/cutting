<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class mattress_pro extends Model {

	protected $fillable = [
        'mattress_id',
        'mattress',
        'style_size',
        'pro_id',
        'pro_pcs_layer',
        'pro_pcs_planned',
        'pro_pcs_actual',
        'damaged_pcs'
    ];

}
