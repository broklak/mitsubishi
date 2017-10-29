<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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
        return isset($data->name) ? $data->name : null;
    }

    public static function getFullName($id) {
        $data = parent::find($id);

        if(isset($data->id)) {
            $typeName = $data->name;
            $model = CarModel::find($data->model_id);
            $modelName = isset($model->name) ? $model->name : null;

            return $modelName.' '.$typeName;
        }

        return null;
    }

    public static function getOptionValue() {
        $data = parent::select(DB::raw('car_types.id as value, CONCAT(car_models.name," ",car_types.name) AS display'))
                        ->join('car_models', 'car_models.id', '=', 'car_types.model_id')
                        ->get();

        return $data;
    }
}
