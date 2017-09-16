<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerImage extends Model
{
    /**
     * @var string
     */
    protected $table = 'customer_image';

    /**
     * @var array
     */
    protected $fillable = [
        'customer_id', 'type', 'id_number', 'filename', 'created_by'
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
