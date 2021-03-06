<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bbn extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'bbn';

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

    public static function getOption() {
        $data = parent::select('id as value', 'name as display')->get();

        $result = [];
        foreach ($data as $key => $value) {
            $result[$key]['value'] = $value->value;
            $result[$key]['display'] = $value->display;
        }

        $others = ['value' => 1000, 'display' => 'Others'];
        array_push($result, $others);
        return $result;
    }
}
