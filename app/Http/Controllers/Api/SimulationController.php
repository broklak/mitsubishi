<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Simulation;
use App\Models\Leasing;
use App\Models\CarType;
use App\Models\CarModel;

class SimulationController extends Controller
{
    public function __construct() {
		$this->middleware('auth:api');
	}

	public function list(Request $request) {
		$simulation = new Simulation();
		$filter = [
			'user_id'	=> Auth::id(),
			'limit'		=> ($request->input('limit')) ? $request->input('limit') : 1000,
			'offset'	=> ($request->input('offset')) ? $request->input('offset') : 0,
			'sort_type' => ($request->input('sort_type')) ? $request->input('sort_type') : 'id',
			'sort_by' => ($request->input('sort_by')) ? $request->input('sort_by') : 'desc'
		];
		$data = $simulation->list($filter);
		$pagination = $this->getPagination($data, $simulation->countList($filter), $filter['offset'], $filter['limit']);

        return $this->apiSuccess($data, $request->input(), $pagination);
	}

	public function detail(Request $request, $id) {
        try {
            $model = new Simulation();
            $simulation = $model->find($id);
            if(!isset($simulation->id)) return $this->apiError($statusCode = 400, "Simulation with $id is not found", 'No result found');

            $leasing = Leasing::find($simulation->leasing_id);
            $carType = CarType::find($simulation->car_type_id);
            $carModel = CarModel::find($simulation->car_model_id);

            $simulation->leasing_name = (isset($leasing->name)) ? $leasing->name : null;
            $simulation->car_model_name = (isset($carType->name)) ? $carType->name : null;
            $simulation->car_type_name = (isset($carModel->name)) ? $carModel->name : null;

        } catch (Exception $e) {
            return $this->apiError($statusCode = 500, $e->getMessage(), 'Something went wrong');
        }

        return $this->apiSuccess($simulation, $request->input());
    }

    public function store(Request $request) {
    	try {
    		$simulation = new Simulation();
    		$validator = Validator::make($request->input(), $this->rules());

    		if ($validator->fails()) {    
                return $this->apiError($statusCode = 400, $validator->messages(), 'Some fields must be filled');
            }
	        $carModel = CarType::getModel($request->input('type_id'));

	        $create = [
	            'leasing_id'  => $request->input('leasing_id'),
	            'car_category_id'  => CarModel::getCategory($carModel),
	            'car_model_id'  => $carModel,
	            'customer_name'  => $request->input('customer_name'),
	            'car_type_id'   => $request->input('type_id'),
	            'car_year'  => $request->input('car_year'),
	            'price'  => parseMoneyToInteger($request->input('total_sales_price')),
	            'dp_amount'  => parseMoneyToInteger($request->input('dp_amount')),
	            'dp_percentage'  => $request->input('dp_percentage'),
	            'duration'  => $request->input('duration'),
	            'admin_cost'  => parseMoneyToInteger($request->input('admin_cost')),
	            'installment_cost'  => parseMoneyToInteger($request->input('installment_cost')),
	            'interest_rate'  => $request->input('interest_rate'),
	            'insurance_cost'  => parseMoneyToInteger($request->input('insurance_cost')),
	            'total_dp'  => parseMoneyToInteger($request->input('total_dp')),
	            'uuid'		=> ($request->input('uuid')) ? $request->input('uuid') : null,
	            'created_by' => Auth::id()
	        ];

	        $head = $simulation->create($create);
	        logUser('Create Simulation '.$head->id);
        	return $this->apiSuccess($head, $request->input(), $pagination = null, $statusCode = 201);
    	} catch (Exception $e) {
    		return $this->apiError($statusCode = 500, $e->getMessage(), 'Something went wrong');	
    	}
    }

    public function update(Request $request, $id) {
    	try {
    		$simulation = new Simulation();
	    	$data = $simulation->find($id);
	        
	        $carModel = CarType::getModel($request->input('type_id'));

	        $update = [
	            'leasing_id'  => $request->input('leasing_id'),
	            'car_category_id'  => CarModel::getCategory($carModel),
	            'car_model_id'  => $carModel,
	            'car_type_id'   => $request->input('type_id'),
	            'customer_name'  => $request->input('customer_name'),
	            'car_year'  => $request->input('car_year'),
	            'price'  => parseMoneyToInteger($request->input('total_sales_price')),
	            'dp_amount'  => parseMoneyToInteger($request->input('dp_amount')),
	            'dp_percentage'  => $request->input('dp_percentage'),
	            'duration'  => $request->input('duration'),
	            'admin_cost'  => parseMoneyToInteger($request->input('admin_cost')),
	            'installment_cost'  => parseMoneyToInteger($request->input('installment_cost')),
	            'interest_rate'  => $request->input('interest_rate'),
	            'insurance_cost'  => parseMoneyToInteger($request->input('insurance_cost')),
	            'total_dp'  => parseMoneyToInteger($request->input('total_dp')),
	            'updated_by' => Auth::id()
	        ];

	        $data->update($update);

	        logUser('Update Simulation '.$id);	
	        return $this->apiSuccess($data, $request->input(), $pagination = null, $statusCode = 200);
    	} catch (Exception $e) {
    		return $this->apiError($statusCode = 500, $e->getMessage(), 'Something went wrong');	
    	}
    }

    public function fields() {
    	$leasing = Leasing::getOption();
    	$carType = CarType::getOptionValue();
    	$field = [
    		generateApiField($fieldName = 'leasing_id', $label = 'Leasing', $type = 'select', $required = true, $options = $leasing),
    		generateApiField($fieldName = 'type_id', $label = 'Car Type', $type = 'select', $required = true, $options = $carType),
    		generateApiField($fieldName = 'car_year', $label = 'Car Year'),
    		generateApiField($fieldName = 'total_sales_price', $label = 'Total Sales Price', $type = 'integer'),
    		generateApiField($fieldName = 'duration', $label = 'Credit Month Duration', $type = 'integer'),
    		generateApiField($fieldName = 'dp_amount', $label = 'DP Amount', $type = 'integer'),
    		generateApiField($fieldName = 'dp_percentage', $label = 'DP Percentage', $type = 'integer'),
    		generateApiField($fieldName = 'total_dp', $label = 'Total Down Payment', $type = 'integer'),
    		generateApiField($fieldName = 'customer_name', $label = 'Customer Name'),
    	];

    	return $this->apiSuccess($field);
    }

    protected function rules() {
        return [
	        'leasing_id'     => 'required',
	        'type_id'     => 'required',
	        'car_year'     => 'required',
	        'total_sales_price'     => 'required',
	        'duration'     => 'required',
	        'dp_amount'     => 'required',
	        'dp_percentage'     => 'required'
        ];
    }
}
