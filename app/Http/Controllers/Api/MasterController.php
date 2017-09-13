<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Banner;
use Exception;

class MasterController extends Controller
{
	public function __construct() {
		$this->middleware('auth:api');
	}

    public function listNews(Request $request) {
        try {
            $limit = ($request->input('limit')) ? $request->input('limit') : 10;
            if($limit < 1) {
                return $this->apiError($statusCode = 400, 'Limit data must be greater than zero', 'Something went wrong with the request');
            }

            $page = ($request->input('page')) ? $request->input('page') : 1;
            $sort = ($request->input('sort')) ? $request->input('sort') : 'desc';
            $query = $request->input('query');
            $news = new News();
            $data = $news->list($query, $sort, $limit, $page);
            $pagination = $this->getPagination($data, $news->count(), $page, $limit);    
        } catch (Exception $e) {
            return $this->apiError($statusCode = 500, $e->getMessage(), 'Something went wrong');          
        }
    	

    	return $this->apiSuccess($data, $request->input(), $pagination);
    }

    public function detailNews(Request $request, $id) {
        try {
            $news = new News();
            $data = $news->select('id', 'title', 'content', 'created_at as dateSystem', 'image')
                         ->find($id);

            if(!isset($data->id)) return $this->apiError($statusCode = 400, "News with ID $id is not found", 'No result found');

            $data->image = asset('images/news/').'/'.$data->image;
            $data->content = strip_tags($data->content);
            $data->dateHuman = date('j F Y', strtotime($data->dateSystem));
        } catch (Exception $e) {
            return $this->apiError($statusCode = 500, $e->getMessage(), 'Something went wrong');       
        }

        return $this->apiSuccess($data);
    }

    public function listBanner(Request $request) {
        try {
            $limit = ($request->input('limit')) ? $request->input('limit') : 10;
            if($limit < 1) {
                return $this->apiError($statusCode = 400, 'Limit data must be greater than zero', 'Something went wrong with the request');
            }
            $page = ($request->input('page')) ? $request->input('page') : 1;
            $sort = ($request->input('sort')) ? $request->input('sort') : 'desc';
            $banner = new Banner();

            $data = $banner->list($sort, $limit, $page);            
            $pagination = $this->getPagination($data, $banner->count(), $page, $limit);
        } catch (Exception $e) {
            return $this->apiError($statusCode = 500, $e->getMessage(), 'Something went wrong');   
        }

        return $this->apiSuccess($data, $request->input(), $pagination);
    }
}
