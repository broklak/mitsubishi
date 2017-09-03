<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FleetRate extends Model
{
    /**
     * @var string
     */
    protected $table = 'fleet_rate';

    /**
     * @var array
     */
    protected $fillable = [
        'rate', 'status', 'updated_by'
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'id';
}
