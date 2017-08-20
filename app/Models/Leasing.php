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
        'name', 'contact_name', 'phone', 'fax', 'email', 'address', 'status', 'created_by', 'updated_by'
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
        return $data->name;
    }
}
