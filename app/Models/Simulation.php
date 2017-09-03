<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'interest_rate', 'insurance_cost', 'total_dp', 'car_type_id', 'created_by'
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'id';
}
