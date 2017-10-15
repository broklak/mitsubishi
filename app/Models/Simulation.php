<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Simulation extends Model
{
    /**
     * @var string
     */
    protected $table = 'simulation';

    /**
     * @var array
     */
    protected $fillable = [
        'leasing_id', 'car_category_id', 'car_model_id', 'car_year', 'price', 'dp_amount', 'dp_percentage', 'duration', 'admin_cost', 'installment_cost',
        'interest_rate', 'insurance_cost', 'total_dp', 'car_type_id', 'created_by', 'customer_name', 'uuid', 'other_cost'
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    public static function list($filter = []) {
        $userId = $filter['user_id'];
        $limit = $filter['limit'];
        $offset = $filter['offset'];
        $sortType = $filter['sort_type'];
        $sortBy = $filter['sort_by'];

        $data = parent::select(DB::raw('simulation.*,simulation.price as total_sales_price,leasing.name as leasing_name'))
                        ->where('simulation.created_by', $userId)
                        ->join('leasing', 'leasing.id', '=', 'simulation.leasing_id')
                        ->orderBy($sortType, $sortBy)
                        ->offset($offset)
                        ->limit($limit)
                        ->get();

        return $data;
    }

    public static function syncList($time) {
        $data = parent::select(DB::raw('simulation.*,simulation.price as total_sales_price,leasing.name as leasing_name'))
                        ->where('simulation.created_at', '>', $time)
                        ->join('leasing', 'leasing.id', '=', 'simulation.leasing_id')
                        ->get();
        return $data;
    }

    public static function countList($filter = []) {
        $userId = $filter['user_id'];

        $count = parent::where('simulation.created_by', $userId)
                        ->join('leasing', 'leasing.id', '=', 'simulation.leasing_id')
                        ->count();

        return $count;
    }

    public static function totalInterest($installmentCost, $duration, $totalSalesPrice, $dpAmount) {
        return ceil(($installmentCost * $duration) - ($totalSalesPrice - $dpAmount));
    }
}
