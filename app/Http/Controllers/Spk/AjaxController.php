<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\LeasingRateHead;
use App\Models\InsuranceRateHead;
use App\Models\CarType;
use App\Models\CarModel;
use App\Models\Customer;
use App\Models\OrderHead;

class AjaxController extends Controller
{
    public function getLeasingFormula(Request $request) {
    	$data['dp'] = $request->input('dp');
    	$data['leasing'] = $request->input('leasing');
    	$data['duration'] = $request->input('duration');
    	$data['carType'] = $request->input('car_type');
    	$data['dealer'] = $request->input('dealer');
    	$data['karoseri'] = $request->input('karoseri');
    	$data['car_year'] = $request->input('car_year');

    	$interestRate = LeasingRateHead::getRate($data);
    	$insuranceRate = InsuranceRateHead::getRate($data);
    	$unpaid = parseMoneyToInteger($request->input('unpaid'));
    	$year = floor($data['duration'] / 12);

    	// INSTALLMENT FORMULA
    	$interest = ($interestRate / 100 * $unpaid) * $year;
    	$unpaidAndInterest = $unpaid + $interest;
    	$installment =  floor($unpaidAndInterest / $data['duration']);

    	// INSURANCE FORMULA
    	$totalSales = parseMoneyToInteger($request->input('total_sales'));
    	$insuranceCost = $totalSales * ($insuranceRate / 100);

    	$return['interest'] = ($interestRate != null) ? $interestRate : 0;
    	$return['insurance'] = ($insuranceRate != null) ? $insuranceCost : 0;
    	$return['installment'] = ($interestRate != null) ? $installment : 0;

    	return json_encode($return);
    }

    public function getCarType(Request $request) {
        $model_id = $request->input('model_id');
        $data = CarType::where('model_id', $model_id)->get();
        $result = [];
        foreach ($data as $key => $value) {
            $result[$key]['id'] = $value->id;
            $result[$key]['value'] = $value->name;
        }

        return json_encode($result);
    }

    public function getCustomerData (Request $request) {
        $phone = $request->input('phone');
        $data = Customer::select(DB::raw("id, first_name, last_name, address, 
                                        (select id_number from customer_image where customer_id = customers.id order by type, id desc limit 1) AS id_number,
                                        (select type from customer_image where customer_id = customers.id order by type, id desc limit 1) AS id_type,
                                        (select filename from customer_image where customer_id = customers.id order by type, id desc limit 1) AS filename"
                                        ))
                        ->where('phone', $phone)
                        ->first();

        return json_encode($data);
    }

    public function getGraphDO(Request $request) {
        $order = new OrderHead();
        $month = ($request->input('month')) ? $request->input('month') : date('m');
        $year = ($request->input('year')) ? $request->input('year') : date('Y');

        $result = $order->graphDo();

        return json_encode($result);
    }

    public function getGraphSPK(Request $request) {
        $order = new OrderHead();
        $month = ($request->input('month')) ? $request->input('month') : date('m');
        $year = ($request->input('year')) ? $request->input('year') : date('Y');

        $result = $order->graphSPK();

        return json_encode($result);
    }
}
