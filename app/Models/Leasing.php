<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leasing extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'leasing';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'contact_name', 'phone', 'fax', 'email', 'admin_cost', 'address', 'status', 'created_by', 'updated_by', 'areas'
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

        $others = ['value' => 0, 'display' => 'Other Leasing'];
        array_push($result, $others);
        return $result;
    }
}
