<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultAdminFee extends Model
{
    /**
     * @var string
     */
    protected $table = 'default_admin_fee';

    /**
     * @var array
     */
    protected $fillable = [
        'cost', 'status', 'created_by', 'updated_by'
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    public static function getCost($leasingId = null) {
    	if($leasingId == null) {
    		$data = parent::find(1);
    		return $data->cost;
    	}

    	$data = Leasing::find($leasingId);
    	return (isset($data->admin_cost)) ? $data->admin_cost : 0;
    }
}
