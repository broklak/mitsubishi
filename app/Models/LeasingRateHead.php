<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeasingRateHead extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'leasing_rate_head';

    /**
     * @var array
     */
    protected $fillable = [
        'leasing_id', 'car_model_id', 'car_type_id', 'areas', 'start_date', 'end_date', 'karoseri', 'created_by', 'updated_by'
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    public static function getRate($data) {
        $now = date('Y-m-d');
        $where[] = ['leasing_id', '=', $data['leasing']];
        $where[] = ['start_date', '<=', $now];
        $where[] = ['end_date', '>=', $now];
        $where[] = ['car_type_id', '=', $data['carType']];

        if($data['karoseri']) {
            $where[] = ['karoseri', '=', $data['karoseri']];            
        }

        $getHead = parent::where($where)->first();
        if(!isset($getHead->id)) {
            return null;
        }
        // GET DETAIL
        $detail = LeasingRateDetail::where('leasing_rate_id', $getHead->id)
                                    ->where('dp_min', '<=', $data['dp'])
                                    ->where('dp_max', '>=', $data['dp'])
                                    ->where('months', $data['duration'])
                                    ->first();

        return (isset($detail->rate)) ? $detail->rate : null;
    }
}
