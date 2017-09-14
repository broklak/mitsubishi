<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    /**
     * @var string
     */
    protected $table = 'delivery_order';

    /**
     * @var array
     */
    protected $fillable = [
        'spk_id', 'spk_doc_code', 'is_fleet', 'do_code', 'do_date'
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    public function list($where = []) {
        $data = parent::select('spk_doc_code', 'do_code', 'do_date', 'total_sales_price', 'spk_id', 'delivery_order.id', 'is_fleet')
                        ->join('order_price', 'order_price.order_id', '=', 'delivery_order.spk_id')
                        ->where($where)
                        ->get();

        return $data;
    }

    public static function notChecked() {
        $data = parent::select('spk_doc_code', 'do_code', 'do_date', 'total_sales_price', 'spk_id', 'delivery_order.id', 'is_fleet')
                        ->join('order_price', 'order_price.order_id', '=', 'delivery_order.spk_id')
                        ->where('is_fleet', '=', null)
                        ->get();

        return $data;
    }

    public static function getType($type) {
        if($type == null) {
            return 'Not Decided';
        }

        if($type == 1) {
            return 'Fleet';
        }

        return 'Not Fleet';
    }

    public function getInsentif($month, $year) {
        $start = $year.'-'.$month.'-01';
        $end = $year.'-'.$month.'-'.date('t', strtotime($start));
        $whereDO[] = ['is_fleet', '<>', null];
        $data = parent::where($whereDO)
                        ->whereBetween('do_date', [$start, $end])
                        ->get();
        
        $insentif = $this->calculateInsentif($data);
        $result = $this->calculateImbalan($insentif);
        $deductedResult = $this->deductInsentif($result);

        return $deductedResult;
    }

    protected function deductInsentif($result) {
        $fleetRate = FleetRate::find(1)->value('rate');
        foreach ($result as $key => $value) {
            $insentifFleet = $value['insentif_fleet'] * $fleetRate / 100; // DEDUCT INSENTIF FLEET FROM FLEET RATE
            $supervisorDeduct = ($insentifFleet + $value['insentif_non_fleet']) * 10 / 100; // 10% RULES FOR SUPERVISOR
            $totalInsentif = $insentifFleet + $value['insentif_non_fleet'] - $supervisorDeduct;
            $result[$key]['total_insentif'] = $totalInsentif;
            $result[$key]['for_supervisor'] = $supervisorDeduct;
            $result[$key]['insentif_fleet'] = $insentifFleet;
            $result[$key]['sales_accepted'] = $value['imbalan'] + $totalInsentif;
        }

        return $result;
    }

    protected function calculateInsentif($data) {
        $result = [];
        foreach ($data as $key => $value) {
            $spk = OrderHead::getInsentifByType($value->spk_id);
            if(!isset($spk->insentif_amount) || $spk->insentif_amount == 0) { // IF INSENTIF IN TYPE IS ZERO, TAKE IT FROM MODEL
                $spk = OrderHead::getInsentifByModel($value->spk_id);
            }
            $insentif = (isset($spk->insentif_amount)) ? $spk->insentif_amount : 0;
            $user = $spk->created_by;

            if(isset($result[$user])) {
                $result[$user]['total_insentif'] += $insentif;
                $result[$user]['sales'] += 1;
                if($value->is_fleet == 2) {
                    $result[$user]['non_fleet'] += 1;
                    $result[$user]['insentif_non_fleet'] += $insentif;
                } else {
                    $result[$user]['fleet'] += 1;
                    $result[$user]['insentif_fleet'] += $insentif;
                }
            } else {
                $result[$user]['total_insentif'] = $insentif;
                $result[$user]['sales'] = 1;
                if($value->is_fleet == 2) {
                    $result[$user]['non_fleet'] = 1;
                    $result[$user]['fleet'] = 0;
                    $result[$user]['insentif_non_fleet'] = $insentif;
                    $result[$user]['insentif_fleet'] = 0;
                } else {
                    $result[$user]['non_fleet'] = 0;
                    $result[$user]['fleet'] = 1;
                    $result[$user]['insentif_non_fleet'] = 0;
                    $result[$user]['insentif_fleet'] = $insentif;
                }
            }
        }

        return $result;
    }

    protected function calculateImbalan($result) {
        $lastFormula = SalesBonusHead::orderBy('id', 'desc')->first();
        if(!isset($lastFormula->id)) { // IF NO FORMULA
            foreach ($result as $key => $value) {
                $result[$key]['imbalan'] = 0;    
            }

            return $result;
        }

        foreach ($result as $key => $value) {
            $imbalan = SalesBonusHead::where('start_date', '<=', date('Y-m-d'))
                                    ->where('end_date', '>=', date('Y-m-d'))
                                    ->orderBy('id', 'desc')
                                    ->first();

            if(!isset($imbalan->id)) { // IF NO VALID RULES THEN TAKE THE LAST ONE
                $imbalan = SalesBonusHead::orderBy('id', 'desc')->first();
            }

            $imbalan_amount = SalesBonusDetail::where('sales_bonus_id', $imbalan->id)
                                            ->where('min_car', '<=', $value['non_fleet'])
                                            ->where('max_car', '>=', $value['non_fleet'])
                                            ->first();
            $result[$key]['imbalan'] = (isset($imbalan_amount->amount)) ? $imbalan_amount->amount : 0;
        }

        return $result;
    }

    public static function validToDO($spkId) {
        // GET ALL DO 
        $do = parent::where('spk_id', $spkId)->count();
        $unitSold = OrderHead::find($spkId);
        $qty = $unitSold->qty;
        
        return ($do < $qty) ? true : false;
    }
}
