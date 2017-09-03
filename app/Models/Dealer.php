<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dealer extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'dealers';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'company_id', 'contact_name', 'phone', 'fax', 'email', 'address', 'status', 'created_by', 'updated_by', 'area'
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
