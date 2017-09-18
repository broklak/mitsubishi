<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarModel extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'car_models';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'category_id', 'insentif_amount', 'status', 'created_by', 'updated_by'
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

    public static function getName($id) {
        $data = parent::find($id);
        return isset($data->name) ? $data->name : null;
    }

    public static function getCategory($id) {
        $data = parent::find($id);
        return (isset($data->category_id)) ? $data->category_id : null;
    }
}
