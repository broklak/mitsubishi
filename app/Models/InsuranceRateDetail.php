<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceRateDetail extends Model
{
    /**
     * @var string
     */
    protected $table = 'insurance_rate_detail';

    /**
     * @var array
     */
    protected $fillable = [
        'insurance_rate_id', 'years', 'rate', 'type', 'created_by', 'updated_by'
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'id';
}
