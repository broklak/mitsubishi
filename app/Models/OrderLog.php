<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    /**
     * @var string
     */
    protected $table = 'order_log';

    /**
     * @var array
     */
    protected $fillable = [
       'order_id', 'desc', 'created_by'
    ];

    /**
     * @var string
     */
}
