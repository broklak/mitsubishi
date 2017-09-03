<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesBonusHead extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'sales_bonus_head';

    /**
     * @var array
     */
    protected $fillable = [
        'start_date', 'end_date', 'created_by', 'updated_by'
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
}
