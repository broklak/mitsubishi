<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'customers';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'first_name', 'last_name', 'phone', 'id_type', 'id_number', 'email', 'address', 'job', 'npwp', 'image', 'status', 'created_by', 'updated_by'
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

    public static function validateSpk($data) {
        $where['id_number'] = $data['id_number'];
        $where['id_type'] = $data['id_type'];
        $validateId = parent::where($where)->first();

        $data = [
            'first_name' => $data['customer_first_name'],
            'last_name' => $data['customer_last_name'],
            'id_type'   => $data['id_type'],
            'id_number' => $data['id_number'],
            'address'   => $data['customer_address'],
            'phone'     => $data['customer_phone'],
            'npwp'      => $data['customer_npwp'],
            'image'     => isset($data['image']) ? $data['image'] : null
        ];

        if(isset($validateId->id)) { // CUSTOMER EXIST THEN UPDATE DATA
            $data['updated_by'] = Auth::id();
            parent::where($where)->first()->update($data);
            return $validateId->id;
        }

        $data['created_by'] = Auth::id();
        $created = parent::create($data);
        return $created->id;
    }

    public static function getName($id) {
        $data = parent::find($id);
        return $data->first_name.' '.$data->last_name;
    }
}
