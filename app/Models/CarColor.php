<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class CarColor extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'car_colors';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'status', 'created_by', 'updated_by'
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
        return (isset($data->name)) ? $data->name : 'Others';
    }

    public static function getOptionValue() {
        $data = parent::select(DB::raw('id as value, name as display'))
                        ->get();

        return $data;
    }
}
