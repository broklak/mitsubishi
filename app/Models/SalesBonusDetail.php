<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesBonusDetail extends Model
{
    /**
     * @var string
     */
    protected $table = 'sales_bonus_detail';

    /**
     * @var array
     */
    protected $fillable = [
        'sales_bonus_id', 'min_car', 'max_car', 'amount', 'created_by', 'updated_by'
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'id';
}
