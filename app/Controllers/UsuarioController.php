<?php

namespace Rapid\Controllers;

use Rapid\Models\User;
use Rapid\Models\Unit;
use Rapid\Models\Block;
use Rapid\Models\Person;
use Rapid\Models\Phone;
use Rapid\Models\Visitor;
use Rapid\Models\Vehicle;
use Rapid\Models\Registry;
use Rapid\Models\VisitorLog;
use League\Csv\Reader;
use Illuminate\Database\Capsule\Manager as DB;

class UsuarioController
{

	protected $container;

	protected $user;

	public function __construct($container)
	{
		$this->container = $container;

        $session = new \SlimSession\Helper;
        $this->user = $session->get('user_logged_session', $email);

	}

	public function __get($property)
	{
		if ($this->container->{$property}) {
			return $this->container->{$property};
		}
	}

	public function index($request, $response)
	{

		if( ! User::isLogged()) {
			header('Location: /login');
			exit;
		}

		$blocks = Block::all();

		$visitor_log = DB::table('visitor_log')
							->join('visitors', 'visitor_log.visitor_id', '=', 'visitors.id')
							->join('people', 'visitor_log.people_id', '=', 'people.id')
							->select('visitor_log.*', 'visitors.name AS visitor_name', 'people.name AS people_name')
							->orderBy('visitor_log.id', 'DESC')
							->limit(10)
							->get();

		return $this->view->render($response, 'home.twig', [
			'blocks' => $blocks,
			'visitor_log' => $visitor_log,
			'user_auth' => $this->user
		]);
	}

	public function admin($request, $response) 
	{

		if( ! User::isLogged()) {
			header('Location: /login');
			exit;
		}

		if( $this->user['type'] == 2) {
			header('Location: /');
			exit;
		}

		$name = Registry::where('key', 'name')->first();
		$name = $name->value;

		$address = Registry::where('key', 'address')->first();
		$address = $address->value;

		$users = User::all();

		return $this->view->render($response, 'admin.twig', [
			'name' => count($name) > 0 ? $name : false ,
			'address' => count($address) > 0 ? $address : false,
			'users' => $users,
			'user_auth' => $this->user
		]);
	
	}

	public function associar($request, $response)
	{

		$directory = __DIR__ . '/../../cache/';
	    $files = $_FILES;

	    if (empty($files['csv'])) {
	        throw new Exception('Expected a newfile');
	    }

	 	if (is_dir($directory) && is_writable($directory)) {
    
    		$filename = md5( "{$files['csv']['name']}rand(0,1000))") . '.csv';

   		    move_uploaded_file($files['csv']['tmp_name'], "$directory$filename");

		} else {
	    	echo 'Pasta cache não é gravável';
	    	exit;
		}

		$csv = Reader::createFromPath( "$directory$filename", 'r');
		$csv->setDelimiter(',');
		$records = $csv->getRecords(); //returns all the CSV records as an Iterator object

		$resultCsv = array();
		foreach($records as $r) {
			$resultCsv[] = $r;
		}

        $session = new \SlimSession\Helper;
        $session->set('associate_file_csv', $resultCsv);		
		
		return $this->view->render($response, 'usuario_associar.twig', [
			'csv' => array_slice($resultCsv, 1, 10),
			'csv_ref' => $resultCsv[0],
			'user_auth' => $this->user
		]);
	}

	public function salvar_associacao($request, $response)
	{

		$session = new \SlimSession\Helper;
		$resultCsv = $session->get('associate_file_csv', false);

		$associacao = $_POST['associacao'];

		$moradorIdx = (int) array_search('morador', $associacao);
		$telefonesIdx = (int) array_search('telefones', $associacao);

		$aptoIdx = (int) array_search('apto', $associacao);
		$nomeaptoIdx = (int) array_search('nome_apto', $associacao);

		$blocoIdx = (int) array_search('bloco', $associacao);

		$modeloCarroIdx = (int) array_search('modelo_carro', $associacao);		
		$corCarroIdx = (int) array_search('cor_carro', $associacao);
		$placaCarroIdx = (int) array_search('placa_carro', $associacao);
		$tipoCarroIdx = (int) array_search('tipo_carro', $associacao);

		$arrayBlocos = array();
		$arrayUnidades = array();
		$arrayMoradores = array();

		foreach($resultCsv as $line) {

			// BLOCO
			$_bloco = $line[$blocoIdx - 1];

			if(isset($arrayBlocos[md5($_bloco)])) {

				$_bloco = $arrayBlocos[md5($_bloco)];
			}
			else{

				$bloco = new Block();
				$bloco->name = $_bloco;
				$bloco->save();
				$arrayBlocos[md5($_bloco)] = $bloco;
				$_bloco = $arrayBlocos[md5($_bloco)];
			}


			// APTO / CASA
			$_apto = $line[$aptoIdx - 1];
			$_nomeapto = $line[$nomeaptoIdx - 1];

			if(isset($arrayUnidades[md5($_apto . $_bloco->name)])) {

				$_apto = $arrayUnidades[md5($_apto . $_bloco->name)];
			}
			else{

				$unidade = new Unit();
				$unidade->number = $_apto;
				$unidade->name = $_nomeapto;
				$unidade->block_id = $_bloco->id;
				$unidade->save();
				$arrayUnidades[md5($_apto . $_bloco->name)] = $unidade;
				$_apto = $arrayUnidades[md5($_apto . $_bloco->name)];
			}

			// MORADOR
			$_morador = $line[$moradorIdx - 1];

			if(isset($arrayMoradores[md5($_morador)])) {

				$_morador = $arrayMoradores[md5($_morador)];
			}
			else{

				$person = new Person();
				$person->name = $_morador;
				$person->unit_id = $_apto->id;
				$person->active = 1;
				$person->type = 1;
				$person->save();
				$arrayMoradores[md5($_morador)] = $person;
				$_morador = $arrayMoradores[md5($_morador)];


				// Telefone
				$_telefones = $line[$telefonesIdx - 1];
				$__telefones = explode(";", $_telefones);
				if(count($__telefones) > 0) {
					foreach($__telefones as $_tel) {
						if( strlen(trim($_tel)) > 0 ) {
							$_phone = new Phone();
							$_phone->type = 1;
							$_phone->number =  $_tel;
							$_phone->people_id = $_morador->id;
							$_phone->save();						
						}
					}
				}				
			}


			// carro
			$_modeloCarro = $line[$modeloCarroIdx - 1];
			$_corCarro = $line[$corCarroIdx - 1];
			$_placaCarro = $line[$placaCarroIdx - 1];
			$_tipoCarro = $line[$tipoCarroIdx - 1];

			if($_modeloCarro) {
				$_vehicle = new Vehicle();
				$_vehicle->type = ( $_tipoCarro ? $_tipoCarro : 1 );
				$_vehicle->model =  $_modeloCarro;
				$_vehicle->plate =  $_placaCarro;
				$_vehicle->color =  $_corCarro;
				$_vehicle->people_id = $_morador->id;
				$_vehicle->save();
			}
		}

		$session->delete('associate_file_csv');
	  	unset($session->associate_file_csv);
	  	unset($session['associate_file_csv']);

		header('Location: /');
		exit;
	}


	public function search($request, $response)
	{

		$search = filter_var($request->getParam('search'), FILTER_SANITIZE_STRING);
		$visitors = Visitor::where('name', 'like', '%' . $search . '%')
					  	   ->orWhere('document', 'like', '%' . $search .'%')->get();

		return json_encode($visitors);
	}

	public function condominio($request, $response)
	{

		if( ! User::isLogged()) {
			header('Location: /login');
			exit;
		}

		if( $this->user['type'] == 2) {
			header('Location: /');
			exit;
		}

		if(isset($_POST['name'])) {

			$registry = Registry::where('key', 'name')->first();

			if($registry->value != '') {
				$registry->value = $_POST['name'];
			}else {
				$registry = new Registry();
				$registry->key = 'name';
				$registry->value = $_POST['name'];
			}
			$registry->save();
		}

		if(isset($_POST['address'])) {

			$registry = Registry::where('key', 'address')->first();

			if($registry->value != '') {
				$registry->value = $_POST['address'];
			}else {
				$registry = new Registry();
				$registry->key = 'address';
				$registry->value = $_POST['address'];
			}
			$registry->save();
			
		}
		header('Location: /admin');
		exit;
	}

	public function store($request, $response)
	{

		if( ! User::isLogged()) {
			header('Location: /login');
			exit;
		}
		
		if( $this->user['type'] == 2) {
			header('Location: /');
			exit;
		}

		// verifica se o eamil ja nao esta cadastrado
		$email = filter_var($request->getParam('email'), FILTER_VALIDATE_EMAIL);
		$registered_user = User::where('email', $email)->first();

		if( ! $registered_user) {
			$user = new User();
			$user->email = $email;
			$user->name = $request->getParam('name');
			$user->password = sha1($request->getParam('password'));
			$user->type = $request->getParam('type');
			$user->save();

			return json_encode(array('success'=>true));

		}else{

			if($registered_user->id == $request->getParam('id')) {
				$registered_user->email = $email;
				$registered_user->name = $request->getParam('name');
				$registered_user->password = sha1($request->getParam('password'));
				$registered_user->type = $request->getParam('type');
				$registered_user->save();			
				
				return json_encode(array('success'=>true));				
			}

		}
		return json_encode(array('success'=>false));
	}

	public function remove($request, $response)
	{

		if( ! User::isLogged()) {
			header('Location: /login');
			exit;
		}

		if( $this->user['type'] == 2) {
			header('Location: /');
			exit;
		}

		$id = filter_var($request->getParam('id'), FILTER_VALIDATE_INT);

		$user = User::where('id', $id)->first();

		if($user->email == 'admin@asgard.com.br') {
		    return json_encode(array('success'=>false));
		}
		$user->delete();

		return json_encode(array('success'=>true));
	}

	public function login($request, $response)
	{
		if(User::isLogged()) {
			header('Location: /');
			exit;			
		}
		return $this->view->render($response, 'login.twig');
	}

	public function login_submit($request, $response)
	{

		if($request->isPost()) {

			$email = filter_var($request->getParam('email'), FILTER_VALIDATE_EMAIL);
			$pass = filter_var($request->getParam('pass'), FILTER_SANITIZE_STRING);

			$valid = User::checkLogin($email, $pass);

			if($valid) {
				header('Location: /');
				exit;
			}

			header('Location: /login');
			exit;
		}

		header('Location: /login');
		exit;
	}

	public function logout($request, $response)
	{
		User::logout();
		header('Location: /login');
		exit;
	}

	public function perfil($request, $response)
	{

		if( ! User::isLogged()) {
			header('Location: /login');
			exit;
		}

		$name = Registry::where('key', 'name')->first();
		$name = $name->value;

		$address = Registry::where('key', 'address')->first();
		$address = $address->value;

		return $this->view->render($response, 'usuario_perfil.twig', [
			'name' => count($name) > 0 ? $name : false ,
			'address' => count($address) > 0 ? $address : false,			
			'user_auth' => $this->user
		]);
	}

	public function getBlocks($request, $response)
	{
		if( ! User::isLogged()) {
			header('Location: /login');
			exit;
		}		
		$blocks = Block::all();
        return $response->withJson(
        	$blocks
        );
	}

	public function getUnitsByBlock($request, $response, $id)
	{

		if( ! User::isLogged()) {
			header('Location: /login');
			exit;
		}		
		$units = Unit::select('id','name')->where('block_id', $id)->get();
        return $response->withJson(
        	$units
        );
	}

	public function getUserById($request, $response, $id)
	{
		if( ! User::isLogged()) {
			header('Location: /login');
			exit;
		}

		$user = User::find($id);
        return $response->withJson(
        	$user
        );
	}

	public function getUsersByUnit($request, $response, $id)
	{
		if( ! User::isLogged()) {
			header('Location: /login');
			exit;
		}
		
		$_users = array();
		$users = Person::where('unit_id', $id)->get();
		
		foreach($users as $user) {
			$_user['name'] = $user['name'];
			$_user['id'] = $user['id'];
			$_phones = $user->phones;
			foreach($_phones as $_phone) {
				$_user['phones'][] = $_phone->number;	
			}			
			$_users[] = $_user;
		}

        return $response->withJson(
        	$_users
        );
	}

	public function getUnitAddress($request, $response, $id) 
	{
		if( ! User::isLogged()) {
			header('Location: /login');
			exit;
		}

		$unit = Unit::where('id', $id)->first();
		$block = Block::where('id', $unit->block_id)->first();

        return $response->withJson(
        		array(
        			'unit' => $unit,
        			'block' => $block,
        		)
        );
	}

	public function getReferenceFile($request, $response)
	{
            $csv = file_get_contents( __DIR__ . '/../../resources/csv/ClickPortaria_Modelo_de_Importacao.csv');
	    $file = 'ClickPortaria_Modelo_de_Importacao.csv';

            header("Content-Type: application/force-download");
            header('Content-Type: "application/octet-stream"');
            header("Content-Type: application/download");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=\"$file\"");
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Pragma: no-cache');
            echo $csv;
            exit;
	}
}
