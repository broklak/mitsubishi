<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrderApproval extends Model
{
    /**
     * @var string
     */
    protected $table = 'order_approval';

    /**
     * @var array
     */
    protected $fillable = [
       'order_id', 'level_approved', 'approved_by', 'job_position_id', 'role_name'
    ];

    public static function getOrderApproval($orderId) {
        $data = parent::where('order_id', $orderId)->get();
        $approval = [];
        foreach ($data as $key => $value) {
            $approval[] = $value->role_name;
        }

        return $approval;
    }

    public static function eligibleToApprove($order) {
        $userData = Auth::user();

        // CHECK IF USER IS ASSIGNED TO DEALER
        $validDealer = UserDealer::where('user_id', $userData['id'])->where('dealer_id', $order->dealer_id)->first();
        if(!isset($validDealer->id)) {
            return false;
        }

        // CHECK IF USER IS ONE OF APPROVER
        if(!$userData->can('approve.spk')) {
            return false;
        }

        // CHECK IF ORDER ID HAS NOT BEEN APPROVED BY THIS USER
        $checkApproval = parent::where('order_id', $order->id)->get();
        foreach ($checkApproval as $key => $value) {
            if($userData->hasRole($value->role_name)) {
                return false;
            }            
        }        

        return true;
    }
}
