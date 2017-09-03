<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalSetting extends Model
{
    /**
     * @var string
     */
    protected $table = 'approval_setting';

    /**
     * @var array
     */
    protected $fillable = [
       'job_position_id', 'level', 'updated_by'
    ];

    public function getNonApproverRole() {
        $all = parent::all();
        $approver = [];
        foreach ($all as $key => $value) {
            $approver[] = $value->job_position_id;
        }

        $nonApprover = Role::whereNotIn('id', $approver)->get();

        return $nonApprover;
    }
}
