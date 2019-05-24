<?php

namespace Rapid\Controllers;

use Rapid\Models\Visitor;
use Rapid\Models\Block;
use Rapid\Models\Unit;
use Rapid\Models\Vehicle;
use Rapid\Models\Person;
use Rapid\Models\Pagination;
use Rapid\Models\VisitorLog;
use Rapid\Models\VisitorLogInformation;
use Illuminate\Database\Capsule\Manager as DB;

class VisitantesController extends Controller
{
	public function index($request, $response)
	{
		if( isset($_POST['btn-search']) ) {
	        $session = new \SlimSession\Helper;
	        $session->set('visitors_filter', filter_var($_POST['search'], FILTER_SANITIZE_STRING));
		}

		if( isset($_POST['btn-clear']) ) {
	        $session = new \SlimSession\Helper;
			$session->delete('visitors_filter');
		  	unset($session->units_filter);
		  	unset($session['visitors_filter']);
		}

        $page = (int)( $args['page'] ? $args['page'] : 1); 

        $session = new \SlimSession\Helper;
        $visitors_filter = $session->get('units_filter', false);

        if($visitors_filter) {
			$visitors = Visitor::where('name', 'like', '%' . $visitors_filter . '%')
								->limit(1)
								->offset(2)
								->paginate(2);

        }else{
        	$visitors = Visitor::where('id', '>', 0)
        						->paginate(20, '*', 2, ($page * 1));
        }

        $pagination = new Pagination($visitors);
        $pagination = $pagination->getHtml(20, $page, 'visitantes');

		return $this->view->render($response, 'visitantes.twig', [
			'visitors' => $visitors,
			'visitors_filter' => ($visitors_filter ? $visitors_filter : false),
//			'total' => $units->total(),
			'pagination' => $pagination,
			'user_auth' => $this->user
		]);
	}

	public function add($request, $response)
	{

		$blocks = Block::all();
		$units = Unit::all();

		return $this->view->render($response, 'visitantes_add.twig', [
			'blocks' => $blocks,
			'units' => $units,
			'user_auth' => $this->user
		]);
	}

	public function edit($request, $response, $id)
	{
		if($id) {
			
			$visitor = Visitor::find($id['id']);

			return $this->view->render($response, 'visitantes_edit.twig', [
				'visitor' => $visitor,
				'user_auth' => $this->user
			]);
		}

		header('Location: /visitantes');
		exit;		
	}


	public function remove($request, $response, $id)
	{
		if($id) {

			$visitor = Visitor::find($id['id']);
			$unit = Unit::find($id['id']);
			$blocks = Block::all();

			return $this->view->render($response, 'visitantes_remove.twig', [
				'visitor' => $visitor,
				'user_auth' => $this->user
			]);
		}

		header('Location: /visitantes');
		exit;
	}


	public function confirm_remove($request, $response, $id)
	{

		$id = $request->getParam('id');

		if($id) {
			$visitor = Visitor::find($id);
			
			foreach($visitor->vehicles as $vehicle) {
				$vehicle->delete();
			}

			$visitor->delete();
		}

		header('Location: /visitantes');
		exit;
	}	

	public function store($request, $response, $id)
	{
		
		$id = $request->getParam('id');
		$img = $request->getParam('img');
		$name = filter_var($request->getParam('name'), FILTER_SANITIZE_STRING);
		$document = filter_var($request->getParam('document'), FILTER_SANITIZE_STRING);

		if($img != "") {

			list($type, $img) = explode(';', $img);
			list(, $img)      = explode(',', $img);
			$data = base64_decode($img);

			$filename = new \DateTime();
			$filename = md5(rand(1,1000) . $filename->format('dmYHis')) . '.png';
			$path = __DIR__ . '/../../public/assets/avatars/';

			file_put_contents( $path . $filename, $data);
		}

		if($_POST['id']) {

			$visitor = Visitor::find($id);
			$visitor->name = $name;
			$visitor->document = $document;
			if($img != '') {
				$visitor->img = $filename;
			}
			$visitor->service_provider = $request->getParam('service_provider');
			$visitor->save();

			foreach($visitor->vehicles as $vehicle) {
				$vehicle->delete();
			}

			if(count($_POST['vehicle']) > 0) {
				foreach($_POST['vehicle'] as $vehicle) {
					if(trim($vehicle['model']) != '') {
						$_vehicle = new Vehicle();
						$_vehicle->type = $vehicle['type'];
						$_vehicle->model =  $vehicle['model'];
						$_vehicle->plate =  $vehicle['plate'];
						$_vehicle->color =  $vehicle['color'];
						$_vehicle->visitor_id = $visitor->id;
						$_vehicle->save();						
					}
				}
			}			

			header('Location: /visitantes');
			exit;
		}

		$visitor = new Visitor();
		$visitor->name = $name;
		$visitor->document = $document;
		if($img != '') {
			$visitor->img = $filename;
		}
		$visitor->service_provider = $request->getParam('service_provider');
		$visitor->save();

		if(count($_POST['vehicle']) > 0) {
			foreach($_POST['vehicle'] as $vehicle) {
				if(trim($vehicle['model']) != '') {
					$_vehicle = new Vehicle();
					$_vehicle->type = $vehicle['type'];
					$_vehicle->model =  $vehicle['model'];
					$_vehicle->plate =  $vehicle['plate'];
					$_vehicle->color =  $vehicle['color'];
					$_vehicle->visitor_id = $visitor->id;
					$_vehicle->save();					
				}
			}
		}

		header('Location: /visitantes');
		exit;
	}

	public function search($request, $response)
	{

		$search = filter_var($request->getParam('search'), FILTER_SANITIZE_STRING);
		$visitors = Visitor::where('name', 'like', '%' . $search . '%')
					  	   ->orWhere('document', 'like', '%' . $search .'%')->get();

		return json_encode($visitors);
	}


	public function registros($request, $response, $args)
	{


		if( isset($_POST['btn-search']) ) {
	        $session = new \SlimSession\Helper;
	        $session->set('visitors_log_filter', filter_var($_POST['search'], FILTER_SANITIZE_STRING));
		}

		if( isset($_POST['start']) ) {
	        $session = new \SlimSession\Helper;
	        $session->set('visitors_log_start', filter_var($_POST['start'], FILTER_SANITIZE_STRING));
		}

		if( isset($_POST['end']) ) {
	        $session = new \SlimSession\Helper;
	        $session->set('visitors_log_end', filter_var($_POST['end'], FILTER_SANITIZE_STRING));
		}		

		if( isset($_POST['btn-clear']) ) {
	        $session = new \SlimSession\Helper;
			$session->delete('visitors_log_filter');
		  	unset($session->visitors_log_filter);
		  	unset($session['visitors_log_filter']);
		}

        $page = (int)( $args['page'] ? $args['page'] : 1); 

        $session = new \SlimSession\Helper;
        $visitors_log_filter = $session->get('visitors_log_filter', false);
        $visitors_log_start = $session->get('visitors_log_start', false);
        $visitors_log_end = $session->get('visitors_log_end', false);

    	if($visitors_log_start == '') {
       		$start = new \DateTime();
    		$start->setTime(0,0,0);
    		$start = $start->format('Y-m-d H:i:s');
    	}else{
    		$start = implode('-', array_reverse(explode("/", $visitors_log_start))) . " 00:00:00";
    		//$start = $start->format('Y-m-d H:i:s');
    	}

    	
    	if($visitors_log_end == '') {
       		$end = new \DateTime();
    		$end->setTime(23,59,59);
    		$end = $end->format('Y-m-d H:i:s');
    	}else{
    		$end = implode('-', array_reverse(explode("/", $visitors_log_end))) . " 23:59:59";
    		//$end = $end->format('Y-m-d H:i:s');
    	}

        if($visitors_log_filter) {

        	$totals[1] = VisitorLogInformation::where('type', 1)        										
        										->whereBetween('created_at', [$start, $end])
        										->where(function($query) {
        											$query->orWhere(
														[
															['model', 'like', '%' . $visitors_log_filter . '%'],
															['color', 'like', '%' . $visitors_log_filter . '%'],
															['plate', 'like', '%' . $visitors_log_filter . '%'],
															['people_name', 'like', '%' . $visitors_log_filter . '%'],
															['visitor_name', 'like', '%' . $visitors_log_filter . '%']
														]
        											);
        										})->count();


        	$totals[2] = VisitorLogInformation::where('type', 2)
        										->whereBetween('created_at', [$start, $end])
        										->where(function($query) {
        											$query->orWhere(
														[
															['model', 'like', '%' . $visitors_log_filter . '%'],
															['color', 'like', '%' . $visitors_log_filter . '%'],
															['plate', 'like', '%' . $visitors_log_filter . '%'],
															['people_name', 'like', '%' . $visitors_log_filter . '%'],
															['visitor_name', 'like', '%' . $visitors_log_filter . '%']
														]
        											);
        										})->count();


        	$totals[3] = VisitorLogInformation::where('type', 3)
        										->whereBetween('created_at', [$start, $end])
        										->where(function($query) {
        											$query->orWhere(
														[
															['model', 'like', '%' . $visitors_log_filter . '%'],
															['color', 'like', '%' . $visitors_log_filter . '%'],
															['plate', 'like', '%' . $visitors_log_filter . '%'],
															['people_name', 'like', '%' . $visitors_log_filter . '%'],
															['visitor_name', 'like', '%' . $visitors_log_filter . '%']
														]
        											);
        										})->count();        										


        	$visitors = VisitorLogInformation::where('model', 'like', '%' . $visitors_log_filter . '%')
			        							->orWhere('color', 'like', '%' . $visitors_log_filter . '%')
			        							->orWhere('plate', 'like', '%' . $visitors_log_filter . '%')
			        							->orWhere('people_name', 'like', '%' . $visitors_log_filter . '%')
			        							->orWhere('visitor_name', 'like', '%' . $visitors_log_filter . '%')
			        						  	->whereBetween('created_at', [$start, $end])
        						  				->paginate(20, '*', 20, ($page * 1));

        }else{

        	$totals[1] = VisitorLogInformation::where('type', 1)
        						->whereBetween('created_at', [$start, $end])->count();

        	$totals[2] = VisitorLogInformation::where('type', 2)
        						->whereBetween('created_at', [$start, $end])->count();

        	$totals[3] = VisitorLogInformation::where('type', 3)
        						->whereBetween('created_at', [$start, $end])->count();

        	$visitors = VisitorLogInformation::where('id', '>', 0)
        						  ->whereBetween('created_at', [$start, $end])
        						  ->orderBy('visitor_log_information.id', 'DESC')
        						  ->paginate(20, '*', 20, ($page * 1));

        }

        $pagination = new Pagination($visitors);
        $pagination = $pagination->getHtml(20, $page, 'visitantes/registros', $count_visitors);

		return $this->view->render($response, 'visitantes_registros.twig', [
			'visitors_log_filter' => $visitors_log_filter,
			'visitors_log_start' => $visitors_log_start,
			'visitors_log_end' => $visitors_log_end,
			'registros' => $visitors,
			'totals' => $totals,
			'pagination' => $pagination,
			'user_auth' => $this->user
		]);

	}
}
