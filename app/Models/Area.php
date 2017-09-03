<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'areas';

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

    public static function getNameByFields($ids) {
    	$ids = explode(',', $ids);
    	$name = [];
    	foreach ($ids as $key => $value) {
    		$data = parent::find($value);
    		if(isset($data->name)) $name[] = $data->name;
    	}
    	return implode(', ', $name);
    }
}
