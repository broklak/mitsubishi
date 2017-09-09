<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'username', 'password', 'last_name', 'job_position_id', 'created_by', 'updated_by', 'status', 'start_work', 'supervisor_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    public static function getName($id) {
        $data = parent::find($id);
        return $data->first_name.' '.$data->last_name;
    }

    public static function salesOwned($userId) {
        $data = parent::where('supervisor_id', $userId)->where('deleted_at', null)->get();
        $result = [];
        foreach ($data as $key => $value) {
            $result[] = $value->id;
        }
        return $result;
    }
}
