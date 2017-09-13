<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class News extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'news';

    /**
     * @var array
     */
    protected $fillable = [
        'title', 'content', 'image', 'status', 'created_by', 'updated_by'
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

    public function list($query = null, $sort = 'desc', $limit = 10, $page = 1) {
        $where = [];
        if($query != null) {
            $where[] = ['title', 'like', "%$query%"];
        }

        $offset = ($page * $limit) - $limit;

        $data = parent::select(DB::raw('id, title, SUBSTRING(content, 1, 50) AS contentShort, image'))
                        ->where($where)
                        ->orderBy('id', $sort)
                        ->offset($offset)
                        ->limit($limit)
                        ->get();
        foreach ($data as $key => $value) {
            $data[$key]->contentShort = strip_tags($value->contentShort);
            $data[$key]->image = asset('images/news/').'/'.$value->image;
        }
        return $data;
    }
}
