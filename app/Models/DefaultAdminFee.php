<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultAdminFee extends Model
{
    /**
     * @var string
     */
    protected $table = 'default_admin_fee';

    /**
     * @var array
     */
    protected $fillable = [
        'cost', 'status', 'created_by', 'updated_by'
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'id';
}
