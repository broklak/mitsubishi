<?php

    /**
     * @param string $messageType
     * @param string $message
     * @return string
     */
    function setDisplayMessage($messageType = 'error', $message = 'Error Message')
    {
        $message = '<div style="margin:20px 0" class="alert alert-' . $messageType . '">' . $message . '</div>';
        return $message;
    }

    /**
     * @param $status
     * @param string $type
     * @return string
     */
    function setActivationStatus($status, $type = 'Active'){
        if($status == 0){
            return '<span class="btn btn-danger">Not '.$type.'</span>';
        }

        return '<span class="btn btn-success">'.$type.'</span>';
    }

    function getIDType($type) {
        if($type == 1) {
            return 'KTP';
        }

        return 'SIM';
    }

    function parseMoneyToInteger($money) {
        if($money == null){
            return 0;
        }
        return str_replace(',', '', $money);
    }

    /**
     * @param $money
     * @return string
     */
    function moneyFormat ($money) {
        $currency = 'Rp. ';
        $money = number_format($money,0,',',',');

        return $money;
    }

    function getPlatType($type) {
        switch ($type) {
            case '1':
                return 'Hitam';
                break;
            case '2':
                return 'Kuning';
                break;
            case '3':
                return 'Merah';
                break;
            default:
                return "Hitam";
                break;
        }
    }
