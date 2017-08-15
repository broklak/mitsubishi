<?php

namespace App\Helpers;

use App\WorkerCategory;
use Illuminate\Http\Request;
use App\Libraries\LayoutManager;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class GlobalHelper
{
    /**
     * @param string $messageType
     * @param string $message
     * @return string
     */
    public static function setDisplayMessage($messageType = 'error', $message = 'Error Message')
    {
        $message = '<div style="margin:20px 0" class="alert alert-' . $messageType . '">' . $message . '</div>';
        return $message;
    }

    /**
     * @param $status
     * @param string $type
     * @return string
     */
    public static function setActivationStatus($status, $type = 'Active'){
        if($status == 0){
            return '<span class="btn btn-danger">Not '.$type.'</span>';
        }

        return '<span class="btn btn-success">'.$type.'</span>';
    }

    public static function getIDType($type) {
        if($type == 1) {
            return 'KTP';
        }

        return 'SIM';
    }
}
