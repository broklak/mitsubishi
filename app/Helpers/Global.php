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
        } else if($type == 2) {
            return 'SIM';
        }

        return 'Passport';
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

    function getMonths() {
        return [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];
    }

    function getPrevYears() {
        $now = date('Y');
        $limit = 3;
        for($i=0; $i < $limit; $i++) {
            $years[] = $now - $i;
        }

        return $years;
    }

    function dateHumanFormat($date) {
        if($date == null) {
            return null;
        }

        return date('j F Y', strtotime($date));
    }

    function logUser($desc) {
        return App\UserLogs::createLog($desc);
    }

    function detailApprover($role, $orderId) {
        $approval = App\Models\OrderApproval::getDetailApprover($role, $orderId);
        if($approval == null) {
            return null;
        }

        $detail = '<span>'.$approval->first_name . ' ' . $approval->last_name . '<br />' . date('j F Y', strtotime($approval->created_at)).'</span><br />';
        if($approval->type == 1) { // APPROVED
            $icon = 'check';
            $button = '';
            $modal = '';
        } else { // REJECTED
            $icon = 'times';
            $button = '<a data-toggle="modal" data-target="#modal-reason-'.$approval->role_name.'" class="btn btn-danger">View Reason</a>';
            $modal = modalRejectReason($approval);
        }

        $html = '<i class="fa fa-'.$icon.' fa-3x"></i><br />'.$detail.$button.$modal;
        return $html;
    }

    function modalRejectReason($approval) {
        return '<div class="modal modal-danger fade" id="modal-reason-'.$approval->role_name.'">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title">Reject SPK '.$approval->spk_code.'</h4>
                            </div>
                            <div class="modal-body">
                                <label for="reject_reason">Reason to reject</label>
                                <textarea id="reject_reason" name="reject_reason" readonly class="form-control">'.$approval->reject_reason.'</textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>';
    }

    function generateApiField($fieldName, $label, $type = 'string', $required = true, $options = null, $desc = null) {
        $field = [
            'fieldName' => $fieldName,
            'type'      => $type,
            'label'     => $label,
            'required'  => $required
        ];

        if($options != null) {
            $field['options'] = $options;
        }

        if($desc != null) {
            $field['desc'] = $desc;
        }

        return $field;
    }
