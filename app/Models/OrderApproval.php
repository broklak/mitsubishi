<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\PermissionRole;

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
       'order_id', 'level_approved', 'approved_by', 'job_position_id', 'role_name', 'type', 'reject_reason'
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

    public static function getDetailApprover($role, $orderId) {
        $data = parent::select('order_approval.created_at', 'users.first_name', 'users.last_name', 'type', 'reject_reason', 'role_name', 'spk_code')
                        ->where('role_name', $role)
                        ->where('order_id', $orderId)
                        ->join('users', 'users.id', '=', 'order_approval.approved_by')
                        ->join('order_head', 'order_head.id', '=', 'order_approval.order_id')
                        ->first();

        return (isset($data->first_name)) ? $data : null;
    }

    public static function getLabelStatus($order) {
        $approver = PermissionRole::getSPKApprover();
        $totalApprover = count($approver);

        $id = $order->id;
        $payment_method = $order->payment_method;
        $unit = $order->qty;
        $data = parent::select('type', 'first_name', 'last_name')
                        ->join('users', 'users.id', '=', 'order_approval.approved_by')
                        ->where('order_id', $id)
                        ->get();


        $totalApproval = count($data);

        if($totalApproval == 0) { // NO APPROVAL YET
            return 'Approve Pending';
        }

        // CHECK DELIVERY ORDER
        $do = self::checkDO($id, $unit);
        if($do) {
            return $do;
        }  

        $cashData['order_id']   = $id;
        $cashData['approval'] = $data;
        $cashData['total_approver'] = $totalApprover;
        if($payment_method == 1) { // CASH    
            return self::cashStatus($cashData);
        } else { // LEASING
            return self::leasingStatus($cashData);
        }

    }

    public static function canEdit($stringStatus) {
        if(stristr($stringStatus, 'Approved') || stristr($stringStatus, 'DO')) {
            return false;
        }

        return true;
    }

    private static function checkDO($id, $unit) {
        $do = DeliveryOrder::where('spk_id', $id)->count();
        if($do == $unit) {
            return 'DO';
        }

        if($do > 0) {
            return 'Partial DO';
        }

        return false;
    }

    private static function cashStatus($data) {
        $id = $data['order_id'];
        $approval = $data['approval'];
        $totalApprover = $data['total_approver'];

        $approved = $rejected = [];
        foreach ($approval as $key => $value) {
            if($value->type == 1) { // APPROVED
                $approved[] = $value->first_name . ' ' . $value->last_name;
            } else { // REJECTED
                $rejected[] = $value->first_name . ' ' . $value->last_name;
            }
        }
        $label = '';
        if(count($approved) > 0) {
            $done = (count($approved) == $totalApprover) ? 'All' : implode('and ', $approved);
            $label .= 'Approved by '.$done.'<br />';
        }

        if(count($rejected) > 0) {
            $done = (count($rejected) == $totalApprover) ? 'All' : implode('and ', $rejected);
            $label .= 'Rejected by '.$done;
        }

        return $label;
    }

    private static function leasingStatus($data) {
        $id = $data['order_id'];
        $approval = $data['approval'];
        $totalApprover = $data['total_approver'];

        $approved = $rejected = [];

        foreach ($approval as $key => $value) {
            if($value->type == 1) { // APPROVED
                $approved[] = $value->first_name . ' ' . $value->last_name;
            } else { // REJECTED
                $rejected[] = $value->first_name . ' ' . $value->last_name;
            }
        }

        if(count($approved) == $totalApprover) {
            return 'Order Pending';
        }

        $label = '';
        if(count($approved) > 0) {
            $label .= 'Approved by '.implode('and ', $approved).'<br />';
        }

        if(count($rejected) > 0) {
            $done = (count($rejected) == $totalApprover) ? 'All' : implode('and ', $rejected);
            $label .= 'Rejected by '.$done;
        }

        return $label;
    }
}
