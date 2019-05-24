<?php

namespace Rapid\Controllers;

use Rapid\Models\Vehicle;
use Rapid\Models\VehicleInformation;
use Rapid\Models\Block;
use Rapid\Models\Unit;
use Rapid\Models\Pagination;
use League\Csv\Writer;
use Illuminate\Database\Capsule\Manager as DB;

class VeiculosController extends Controller
{
	public function index($request, $response)
	{
		if( isset($_POST['btn-search']) ) {
	        $session = new \SlimSession\Helper;
	        $session->set('vehicles_filter', filter_var($_POST['search'], FILTER_SANITIZE_STRING));
		}

		if( isset($_POST['btn-clear']) ) {
	        $session = new \SlimSession\Helper;
			$session->delete('vehicles_filter');
		  	unset($session->vehicles_filter);
		  	unset($session['vehicles_filter']);
		}

        $page = (int)( $args['page'] ? $args['page'] : 1); 

        $session = new \SlimSession\Helper;
        $vehicles_filter = $session->get('vehicles_filter', false);

        if($vehicles_filter) {
			$vehicles = VehicleInformation::where('model', 'like', '%' . $vehicles_filter . '%')
										->orWhere('plate', 'like', '%' . $vehicles_filter .'%')
										->orWhere('color', 'like', '%' . $vehicles_filter .'%')
										->orWhere('people_name', 'like', '%' . $vehicles_filter .'%')
										->orWhere('visitor_name', 'like', '%' . $vehicles_filter .'%')
										->limit(1)
										->offset(0)
										->paginate(20);

        }else{
        	$vehicles = VehicleInformation::where('id', '>', 0)
        						->paginate(20, '*', 20, ($page * 1));
        }

        $blocks = Block::all();

        $pagination = new Pagination($vehicles);
        $pagination = $pagination->getHtml(20, $page, 'veiculos');

		return $this->view->render($response, 'veiculos.twig', [
			'vehicles' => $vehicles,
			'vehicles_filter' => ($vehicles_filter ? $vehicles_filter : false),
			'pagination' => $pagination,
			'blocks' => $blocks,
			'user_auth' => $this->user
		]);
	}

	public function add($request, $response)
	{

		$blocks = Block::all();
		$units = Unit::all();

		return $this->view->render($response, 'veiculos_add.twig', [
			'blocks' => $blocks,
			'units' => $units,
			'user_auth' => $this->user
		]);
	}

	public function edit($request, $response, $id)
	{
		if($id) {
			
			$vehicle = Vehicle::find($id['id']);

			return $this->view->render($response, 'veiculos_edit.twig', [
				'vehicle' => $vehicle,
				'user_auth' => $this->user
			]);
		}

		header('Location: /veiculos');
		exit;		
	}


	public function remove($request, $response, $id)
	{
		if($id) {
			
			$vehicle = Vehicle::find($id['id']);

			return $this->view->render($response, 'veiculos_remove.twig', [
				'vehicle' => $vehicle,
				'user_auth' => $this->user
			]);
		}

		header('Location: /veiculos');
		exit;
	}

	public function exportar($request, $response, $id)
	{

		if($id) {

			$block = Block::where('id', $id['id'])->first();

			if($id == 0) {

				$vehicles = DB::table('vehicles')
								->join('people', 'vehicles.people_id', '=', 'people.id')
								->join('units', 'units.id', '=', 'people.unit_id')
								->select('vehicles.id','vehicles.plate','vehicles.model','vehicles.color','units.number', 'people.name')
								->orderBy('vehicles.id', 'DESC')
								->get();

				$filename = "Veiculos_Todos_.csv";

			}else{

				$vehicles = DB::table('vehicles')
								->join('people', 'vehicles.people_id', '=', 'people.id')
								->join('units', 'units.id', '=', 'people.unit_id')
								->where('units.block_id', '=', "{$id['id']}")
								->select('vehicles.id','vehicles.plate','vehicles.model','vehicles.color','units.number', 'people.name')
								->orderBy('vehicles.id', 'DESC')
								->get();

				$filename = "Veiculos_" . $block->name . ".csv";
			}

			$_vehicles = array();
			foreach($vehicles as $vehicle) {
				$_vehicles[] = array($vehicle->id, $vehicle->plate, '1', $vehicle->model, $vehicle->color, $vehicle->number, $vehicle->name);
			}

			$csv = Writer::createFromString('');
			$csv->insertAll($_vehicles);
	    	
            header("Content-Type: application/force-download");
            header('Content-Type: "application/octet-stream"');
            header("Content-Type: application/download");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Pragma: no-cache');

            echo $csv->getContent();
            $csv->output($filename);
            exit;
		}

	}


	public function confirm_remove($request, $response, $id)
	{

		$id = $request->getParam('id');

		if($id) {
			$vehicle = Vehicle::find($id['id']);
			$vehicle->delete();
		}

		header('Location: /veiculos');
		exit;
	}	

	public function store($request, $response, $id)
	{
		
		$id = $request->getParam('id');
		$model = filter_var($request->getParam('model'), FILTER_SANITIZE_STRING);
		$color = filter_var($request->getParam('color'), FILTER_SANITIZE_STRING);
		$plate = filter_var($request->getParam('plate'), FILTER_SANITIZE_STRING);
		$type = filter_var($request->getParam('type'), FILTER_VALIDATE_INT);

		if($_POST['id']) {

			$vehicle = Vehicle::find($id);
			$vehicle->model = $model;
			$vehicle->color = $color;
			$vehicle->type = $type;
			$vehicle->plate = $plate;
			$vehicle->save();

			header('Location: /veiculos');
			exit;
		}

		$vehicle = new Vehicle();
		$vehicle->model = $model;
		$vehicle->color = $color;
		$vehicle->plate = $plate;
		$vehicle->save();

		header('Location: /veiculos');
		exit;
	}

	public function search($request, $response)
	{

		$search = filter_var($request->getParam('search'), FILTER_SANITIZE_STRING);
		$vehicles = Vehicle::where('name', 'like', '%' . $search . '%')->get();

		return json_encode($vehicles);

	}
}
