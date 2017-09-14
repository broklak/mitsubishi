<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCredit extends Model
{
    /**
     * @var string
     */
    protected $table = 'order_credit';

    /**
     * @var array
     */
    protected $fillable = [
       'order_id', 'leasing_id', 'year_duration', 'owner_name', 'interest_rate', 'admin_cost', 'insurance_cost', 'installment_cost',
        'other_cost', 'total_down_payment', 'status', 'created_by', 'updated_by'
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    public static function createData($data) {
    	return parent::create([
    		'order_id'			=> $data['order_id'],
    		'leasing_id'		=> $data['leasing_id'],
    		'year_duration'		=> $data['credit_duration'],
    		'owner_name'		=> $data['credit_owner_name'],
    		'interest_rate'		=> $data['interest_rate'],
    		'admin_cost'        => parseMoneyToInteger($data['admin_cost']),
            'insurance_cost'    => parseMoneyToInteger($data['insurance_cost']),
            'installment_cost'  => parseMoneyToInteger($data['installment_cost']),
            'other_cost'        => parseMoneyToInteger($data['other_cost']),
            'total_down_payment'        => parseMoneyToInteger($data['total_down_payment']),
    		'status'			=> 0,
    		'created_by'		=> $data['created_by']
    	]);
    }
}
