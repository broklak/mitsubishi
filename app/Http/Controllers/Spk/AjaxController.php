<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LeasingRateHead;
use App\Models\InsuranceRateHead;
use App\Models\CarType;
use App\Models\CarModel;


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
        $term = $request->input('term');
        $data = CarType::select('car_types.id', 'car_types.name as typeName', 'car_models.name as modelName')
                        ->where('car_types.name', 'like', "%$term%")
                        ->orWhere('car_models.name', 'like', "%$term%")
                        ->join('car_models', 'car_types.model_id', '=', 'car_models.id')
                        ->get();
        $result = [];
        foreach ($data as $key => $value) {
            $result[$key]['id'] = $value->id;
            $result[$key]['value'] = $value->modelName.' '.$value->typeName;
        }

        return $result;
    }
}
