<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\PermissionRole;
use App\RoleUser;
use App\Mail\OrderNotification;
use App\User;
use Illuminate\Support\Facades\Mail;

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
            return 'Approval Pending';
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

    public static function canEdit($order) {
        $userData = Auth::user();
        $stringStatus = self::getLabelStatus($order);

        if(stristr($stringStatus, 'Approved') || stristr($stringStatus, 'DO') || stristr($stringStatus, 'Order')) {
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

    public static function sendEmailNotif($type, $order) {
        $user = Auth::user();
        $isSupervisor = $user->hasRole('supervisor');
        $isManager = $user->hasRole('manager');
        $isSuperUser = $user->hasRole('super_admin');
        $dealer_id = $order->dealer_id;

        if($type == 'create' || $type == 'update') { // CREATE
            if($isSupervisor || $isManager || $isSuperUser) {
                return false;
            }

            // FIND APPROVER
            $approver = self::findValidApprover($user->id, $dealer_id); // FIND ALL APPROVER FROM SAME BRANCH
            foreach ($approver as $key => $value) {
                $data = User::find($value);
                if(isset($data->email) && !empty($data->email)) {
                    Mail::to($data->email)->send(new OrderNotification($type, $order, $data));   
                }
            }
        }
    }

    public static function findValidApprover($salesId, $dealerId) {
        // FIND ROLE APPROVE SPK
        $data = RoleUser::select('role_user.user_id')
                            ->where('permission_id', 50)
                            ->where('dealer_id', $dealerId)
                            ->join('permission_role', 'permission_role.role_id', '=', 'role_user.role_id')
                            ->join('user_dealer', 'user_dealer.user_id', '=', 'role_user.user_id')
                            ->get();

        $approver = [];
        foreach ($data as $key => $value) {
            $approver[] = $value->user_id;
        }

        return $approver;
    }
}
