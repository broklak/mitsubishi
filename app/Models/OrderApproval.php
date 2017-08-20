<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderApproval extends Model
{
    /**
     * @var string
     */
    protected $table = 'order_approval';

    /**
     * @var array
     */
    protected $fillable = [
       'order_id', 'level_approved', 'approved_by'
    ];
}
