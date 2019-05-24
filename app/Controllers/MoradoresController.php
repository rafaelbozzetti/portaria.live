<?php

namespace Rapid\Controllers;

use Rapid\Models\Person;
use Rapid\Models\PersonInformation;
use Rapid\Models\Block;
use Rapid\Models\Unit;
use Rapid\Models\Phone;
use Rapid\Models\Vehicle;
use Rapid\Models\Pagination;

class MoradoresController extends Controller
{
	public function index($request, $response, $args)
	{
		if( isset($_POST['btn-search']) ) {
	        $session = new \SlimSession\Helper;
	        $session->set('residents_filter', filter_var($_POST['search'], FILTER_SANITIZE_STRING));
		}

		if( isset($_POST['btn-clear']) ) {
	        $session = new \SlimSession\Helper;
			$session->delete('residents_filter');
		  	unset($session->units_filter);
		  	unset($session['residents_filter']);
		}

        $page = (int)( $args['page'] ? $args['page'] : 1); 

        $session = new \SlimSession\Helper;
        $residents_filter = $session->get('residents_filter', false);

        if($residents_filter) {
			$residents = PersonInformation::where('name', 'like', '%' . $residents_filter . '%')
								->orWhere('email', 'like', '%' . $residents_filter . '%')
								->orWhere('plate', 'like', '%' . $residents_filter . '%')
								->orWhere('unit_name', 'like', '%' . $residents_filter . '%')
								->orWhere('block_name', 'like', '%' . $residents_filter . '%')
								->orWhere('color', 'like', '%' . $residents_filter . '%')
								->orWhere('model', 'like', '%' . $residents_filter . '%')
								->where('type', 1)
								->orderBy('id', 'desc')
								->paginate(20, '*', 20, ($page * 1));

        }else{
        	$residents = PersonInformation::where('type', 1)
        						->orderBy('id', 'desc')
        						->paginate(20, '*', 20, ($page * 1));
        }

        $pagination = new Pagination($residents);
        $pagination = $pagination->getHtml(20, $page, 'moradores');

		return $this->view->render($response, 'moradores.twig', [
			'residents' => $residents,
			'residents_filter' => ($residents_filter ? $residents_filter : false),
			'pagination' => $pagination,
			'user_auth' => $this->user
		]);
	}

	public function add($request, $response)
	{

		return $this->view->render($response, 'moradores_add.twig', [
			'user_auth' => $this->user
		]);
	}

	public function edit($request, $response, $id)
	{
		if($id) {
			
			$resident = Person::find($id['id']);

			return $this->view->render($response, 'moradores_edit.twig', [
				'resident' => $resident,
				'user_auth' => $this->user
			]);
		}

		header('Location: /moradores');
		exit;		
	}


	public function remove($request, $response, $id)
	{
		if($id) {

			$resident = Person::find($id['id']);

			return $this->view->render($response, 'moradores_remove.twig', [
				'resident' => $resident,
				'user_auth' => $this->user
			]);
		}

		header('Location: /moradores');
		exit;
	}


	public function confirm_remove($request, $response, $id)
	{

		$id = $request->getParam('id');

		if($id) {
			$person = Person::find($id);
			foreach($person->phones as $phone) {
				$phone->delete();
			}
			foreach($person->vehicles as $vehicle) {
				$vehicle->delete();
			}

			$path = __DIR__ . '/../../public/assets/avatars/';
			if($person->img != '') {
				shell_exec("rm $path" . $person->img);	
			}			
			$person->delete();
		}

		header('Location: /moradores');
		exit;
	}	

	public function store($request, $response, $id)
	{
		$id = $request->getParam('id');
		$img = $request->getParam('img');
		$name = filter_var($request->getParam('name'), FILTER_SANITIZE_STRING);
		$email = filter_var($request->getParam('email'), FILTER_SANITIZE_STRING);
		$unit = filter_var($request->getParam('unit'), FILTER_SANITIZE_STRING);

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

			$person = Person::find($id);
			$person->name = $name;
			$person->email = $email;
			$person->unit_id = $unit;
			$person->active = 1;
			$person->type = 1;
			if($img != '') {
				$person->img = $filename;
			}
			$person->save();

			foreach($person->phones as $phone) {
				$phone->delete();
			}

			if(count($_POST['phone']) > 0) {
				foreach($_POST['phone'] as $phone) {
					if(trim($phone) != '') {
						$_phone = new Phone();
						$_phone->type = 1;
						$_phone->number =  $phone;
						$_phone->people_id = $person->id;
						$_phone->save();
					}
				}
			}

			foreach($person->vehicles as $vehicle) {
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
						$_vehicle->people_id = $person->id;
						$_vehicle->save();						
					}
				}
			}

			header('Location: /moradores');
			exit;
		}

		$person = new Person();
		$person->name = $name;
		$person->email = $email;
		if($img != '') {
			$person->img = $filename;
		}
		$person->img = $filename;
		$person->unit_id = $unit;
		$person->active = 1;
		$person->type = 1;
		$person->save();

		if(count($_POST['phone']) > 0) {
			foreach($_POST['phone'] as $phone) {
				if(trim($phone) != '') {
					$_phone = new Phone();
					$_phone->type = 1;
					$_phone->number =  $phone;
					$_phone->people_id = $person->id;
					$_phone->save();					
				}
			}
		}

		if(count($_POST['vehicle']) > 0) {
			foreach($_POST['vehicle'] as $vehicle) {
				if(trim($vehicle['model']) != '') {
					$_vehicle = new Vehicle();
					$_vehicle->type = $vehicle['type'];
					$_vehicle->model =  $vehicle['model'];
					$_vehicle->plate =  $vehicle['plate'];
					$_vehicle->color =  $vehicle['color'];
					$_vehicle->people_id = $person->id;
					$_vehicle->save();					
				}
			}
		}

		header('Location: /moradores');
		exit;
	}
}
