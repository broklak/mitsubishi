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
use App\Models\ServerSecret;

class SimulationController extends Controller
{
    public function __construct() {
		$this->middleware('auth:api', ['except' => ['sync']]);
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
		$data = $this->filterListResponse($data);
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

	        $create = [
	            'leasing_id'  => $request->input('leasing_id'),
	            'car_category_id'  => 0,
	            'car_model_id'  => 0,
	            'customer_name'  => $request->input('customer_name'),
	            'car_type_id'   => 0,
	            'car_year'  => 2017,
	            'price'  => parseMoneyToInteger($request->input('total_sales_price')),
	            'dp_amount'  => parseMoneyToInteger($request->input('dp_amount')),
	            'dp_percentage'  => $request->input('dp_percentage'),
	            'duration'  => $request->input('duration'),
	            'admin_cost'  => parseMoneyToInteger($request->input('admin_cost')),
	            'installment_cost'  => parseMoneyToInteger($request->input('installment_cost')),
	            'interest_rate'  => $request->input('interest_rate'),
	            'insurance_cost'  => parseMoneyToInteger($request->input('insurance_cost')),
	            'other_cost'  => parseMoneyToInteger($request->input('other_cost')),
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

	        $update = [
	            'leasing_id'  => $request->input('leasing_id'),
	            'car_category_id'  => 0,
	            'car_model_id'  => 0,
	            'customer_name'  => $request->input('customer_name'),
	            'car_type_id'   => 0,
	            'car_year'  => 2017,
	            'price'  => parseMoneyToInteger($request->input('total_sales_price')),
	            'dp_amount'  => parseMoneyToInteger($request->input('dp_amount')),
	            'dp_percentage'  => $request->input('dp_percentage'),
	            'duration'  => $request->input('duration'),
	            'admin_cost'  => parseMoneyToInteger($request->input('admin_cost')),
	            'installment_cost'  => parseMoneyToInteger($request->input('installment_cost')),
	            'interest_rate'  => $request->input('interest_rate'),
	            'insurance_cost'  => parseMoneyToInteger($request->input('insurance_cost')),
	            'other_cost'  => parseMoneyToInteger($request->input('other_cost')),
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

    public function destroy($id)
    {
    	try {
    		$simulation = new Simulation();
	        $data = $simulation->where('uuid', $id)->first();

	        if(!isset($data->id)) return $this->apiError($statusCode = 400, "Simulation with uuid $id is not found", 'No result found');

	        $data->delete();
	        $messages['internalMessage'] = "Success to delete simulation with uuid $id";
	        $messages['userMessage'] = "Success to delete simulation with uuid $id";
	        logUser('Delete Simulation From API'.$id);
	        return $this->apiSuccess($messages, $request = null, $pagination = null, $statusCode = 200);
    	} catch (Exception $e) {
    		return $this->apiError($statusCode = 500, $e->getMessage(), 'Something went wrong');	
    	}
    }

    public function fields() {
    	$leasing = Leasing::getOption();
    	$carType = CarType::getOptionValue();
    	$field = [
    		generateApiField($fieldName = 'leasing_id', $label = 'Leasing', $type = 'select', $required = true, $options = $leasing),
    		generateApiField($fieldName = 'customer_name', $label = 'Nama Pemesan'),
    		generateApiField($fieldName = 'total_sales_price', $label = 'Harga Mobil', $type = 'integer'),
    		generateApiField($fieldName = 'duration', $label = 'Lama Kredit', $type = 'integer'),
    		generateApiField($fieldName = 'dp_amount', $label = 'DP (Rp)', $type = 'integer'),
    		generateApiField($fieldName = 'dp_percentage', $label = 'DP (%)', $type = 'integer'),
    		generateApiField($fieldName = 'interest_rate', $label = 'Suku Bunga (%)', $type = 'float'),
    		generateApiField($fieldName = 'installment_cost', $label = 'Cicilan Perbulan', $type = 'integer'),
    		generateApiField($fieldName = 'admin_cost', $label = 'Biaya Administrasi', $type = 'integer'),
    		generateApiField($fieldName = 'insurance_cost', $label = 'Biaya Asuransi', $type = 'integer'),
    		generateApiField($fieldName = 'other_cost', $label = 'Biaya Lain Lain', $type = 'integer'),
    		generateApiField($fieldName = 'total_dp', $label = 'TDP', $type = 'integer')
    	];

    	return $this->apiSuccess($field);
    }

    protected function rules() {
        return [
	        'leasing_id'     => 'required',
	        'total_sales_price'     => 'required',
	        'duration'     => 'required',
	        'dp_amount'     => 'required',
	        'dp_percentage'     => 'required'
        ];
    }

    protected function filterListResponse($data) {
    	$model = new Simulation();
    	foreach ($data as $key => $value) {
    		unset($data[$key]['car_category_id']);
    		unset($data[$key]['car_model_id']);
    		unset($data[$key]['car_type_id']);
    		unset($data[$key]['car_year']);
    		unset($data[$key]['car_model_name']);
    		unset($data[$key]['car_type_name']);
    		unset($data[$key]['price']);
    		$data[$key]['total_interest'] = $model->totalInterest($value['installment_cost'], $value['duration'], $value['total_sales_price'], $value['dp_amount']);
    		$data[$key]['sisa_payment'] = $value['total_sales_price'] - $value['dp_amount'];
    		$data[$key]['total_payment'] = $data[$key]['total_interest'] + $data[$key]['sisa_payment'];
    	}

    	return $data;
    }

    public function sync(Request $request) {
        $secret = $request->input('secret');

        $validSecret = ServerSecret::find(1)->value('secret');

        if($secret != $validSecret) {
            return $this->apiError($statusCode = 401, 'Wrong Server Secret', 'Unauthenticated');   
        }

        $data = Simulation::syncList($request->input('timestamp'));

        $data = $this->filterListResponse($data);

        return $this->apiSuccess($data, $request->input());
    }
}
