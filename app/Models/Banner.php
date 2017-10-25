<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Banner extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'banners';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'file', 'status', 'created_by', 'updated_by'
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

    public function list($sort = 'desc', $limit = 10, $page = 1) {
        $offset = ($page * $limit) - $limit;

        $data = parent::select(DB::raw('id, name, file'))
                        ->orderBy('id', $sort)
                        ->where('status', 1)
                        ->where('deleted_at', null)
                        ->offset($offset)
                        ->limit($limit)
                        ->get();

        foreach ($data as $key => $value) {
            $data[$key]->file = asset('images/banner/').'/'.$value->file;
        }

        return $data;
    }
}
