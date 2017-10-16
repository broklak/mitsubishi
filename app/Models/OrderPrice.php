<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class OrderPrice extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'order_price';

    /**
     * @var array
     */
    protected $fillable = [
        'order_id', 'price_off', 'price_on', 'cost_surat', 'discount', 'total_sales_price', 'down_payment_amount', 
        'down_payment_percentage', 'down_payment_date', 'jaminan_cost_amount', 'jaminan_cost_percentage', 'total_unpaid', 'payment_method',
        'status', 'created_by', 'updated_by'
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public static function createData($data) {
    	return parent::create([
    		'order_id'			=> $data['order_id'],
    		'price_off'			=> ($data['price_type'] == 2) ? parseMoneyToInteger($data['price_off']) : 0,
    		'price_on'			=> ($data['price_type'] == 1) ? parseMoneyToInteger($data['price_on']) : 0,
    		'cost_surat'		=> ($data['price_type'] == 2) ? parseMoneyToInteger($data['cost_surat']) : 0,
    		'discount'			=> parseMoneyToInteger($data['discount']),
    		'total_sales_price' => parseMoneyToInteger($data['total_sales_price']),
    		'down_payment_amount' => parseMoneyToInteger($data['booking_fee']),
    		'down_payment_date' => isset($data['down_payment_date']) ? $data['down_payment_date'] : null,
    		'jaminan_cost_amount' => parseMoneyToInteger($data['dp_amount']),
    		'jaminan_cost_percentage' => $data['dp_percentage'],
    		'total_unpaid' 		=> parseMoneyToInteger($data['total_unpaid']),
    		'payment_method' 	=> $data['payment_method'],
    		'status'			=> 0,
    		'created_by'		=> $data['created_by']
    	]);
    }

    public static function updateData($orderId, $data) {
        return parent::where('order_id', $orderId)->update([
            'price_off'         => ($data['price_type'] == 2) ? parseMoneyToInteger($data['price_off']) : 0,
            'price_on'          => ($data['price_type'] == 1) ? parseMoneyToInteger($data['price_on']) : 0,
            'cost_surat'        => ($data['price_type'] == 2) ? parseMoneyToInteger($data['cost_surat']) : 0,
            'discount'          => parseMoneyToInteger($data['discount']),
            'total_sales_price' => parseMoneyToInteger($data['total_sales_price']),
            'down_payment_amount' => parseMoneyToInteger($data['booking_fee']),
            'down_payment_date' => $data['down_payment_date'],
            'jaminan_cost_amount' => parseMoneyToInteger($data['dp_amount']),
            'jaminan_cost_percentage' => $data['dp_percentage'],
            'total_unpaid'      => parseMoneyToInteger($data['total_unpaid']),
            'payment_method'    => $data['payment_method'],
            'updated_by'        => $data['updated_by']
        ]);
    }

}
