<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuranceRateHead extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'insurance_rate_head';

    /**
     * @var array
     */
    protected $fillable = [
        'leasing_id', 'car_model_id', 'car_category_id', 'area', 'created_by', 'updated_by'
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

    public static function getRate($data) {
        $now = date('Y-m-d');
        $modelId = CarType::getModel($data['carType']);
        $categoryId = CarModel::getCategory($data['carType']);
        $car_year = date('Y') - $data['car_year'] ;
        $year = ($car_year == 0) ? 1 : $car_year;
        $where[] = ['leasing_id', '=', $data['leasing']];
        $where[] = ['car_model_id', '=', $modelId];
        $where[] = ['car_category_id', '=', $categoryId];

        $getHead = parent::where($where)->first();
        if(!isset($getHead->id)) {
            return null;
        }
        // GET DETAIL
        $detail = InsuranceRateDetail::where('insurance_rate_id', $getHead->id)
                                    ->where('years', $year)
                                    ->where('type', 1) // ALL RISK DEFAULT
                                    ->first();

        return (isset($detail->rate)) ? $detail->rate : null;
    }
}
