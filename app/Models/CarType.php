<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarType extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'car_types';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'model_id', 'status', 'insentif_amount', 'created_by', 'updated_by'
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

    public static function getModel($id) {
        $data = parent::find($id);
        return (isset($data->model_id)) ? $data->model_id : 0;
    }

    public static function getName($id) {
        $data = parent::find($id);
        return $data->name;
    }
}
