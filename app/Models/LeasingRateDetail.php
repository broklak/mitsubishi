<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeasingRateDetail extends Model
{
    /**
     * @var string
     */
    protected $table = 'leasing_rate_detail';

    /**
     * @var array
     */
    protected $fillable = [
        'leasing_rate_id', 'dp_min', 'dp_max', 'rate', 'months', 'created_by', 'updated_by'
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'id';
}
