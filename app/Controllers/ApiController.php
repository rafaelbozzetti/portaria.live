<?php

namespace Rapid\Controllers;

use Rapid\Models\User;
use Rapid\Models\Unit;
use Rapid\Models\Block;
use Rapid\Models\Person;
use Rapid\Models\Visitor;
use Rapid\Models\Vehicle;
use Rapid\Models\VisitorLog;
use Rapid\Models\Registry;

class ApiController
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

		return $this->view->render($response, 'home.twig', [
			'blocks' => $blocks,
			'user_auth' => $this->user
		]);
	}

	public function visitorAdd($request, $response)
	{
		$name = filter_var($request->getParam('visitor_name'), FILTER_SANITIZE_STRING);
		$document = filter_var($request->getParam('visitor_document'), FILTER_SANITIZE_STRING);
		$service_provider = filter_var($request->getParam('service_provider'), FILTER_SANITIZE_STRING);
		$img = $request->getParam('img');

		if($img != "") {

			list($type, $img) = explode(';', $img);
			list(, $img)      = explode(',', $img);
			$data = base64_decode($img);

			$filename = new \DateTime();
			$filename = md5(rand(1,1000) . $filename->format('dmYHis')) . '.png';
			$path = __DIR__ . '/../../public/assets/avatars/';

			file_put_contents( $path . $filename, $data);
		}


		$visitor = new Visitor();
		$visitor->name = $name;
		$visitor->document = $document;
		if($img != '') {
			$visitor->img = $filename;
		}		
		$visitor->service_provider = $service_provider;
		$visitor->save();

		$model = filter_var($request->getParam('model'), FILTER_SANITIZE_STRING);
		$plate = filter_var($request->getParam('plate'), FILTER_SANITIZE_STRING);
		$color = filter_var($request->getParam('color'), FILTER_SANITIZE_STRING);
		$type = filter_var($request->getParam('type'), FILTER_SANITIZE_STRING);

		if($model != '') {
			$vehicle = new Vehicle();
			$vehicle->model = $model;
			$vehicle->plate = $plate;
			$vehicle->color = $color;
			$vehicle->type = $type;
			$vehicle->visitor_id = $visitor->id;
			$vehicle->save();
		}

		return $response->withJson(
			[
				'success' => true,
				'data' => $visitor
			]
		);
	}

	public function registerVisitor($request, $response)
	{
		$unit = filter_var($request->getParam('unit'), FILTER_VALIDATE_INT);
		$visitor = filter_var($request->getParam('visitor'), FILTER_VALIDATE_INT);
		$person = filter_var($request->getParam('person'), FILTER_VALIDATE_INT);
		$type = filter_var($request->getParam('type'), FILTER_VALIDATE_INT);

		$visitor = Visitor::where('id', $visitor)->first();
		$vehicle = ($visitor->vehicles ? $visitor->vehicles : null);
		if($vehicle) {
			$vehicle = $vehicle[0]->id;
		}

		$visitorlog = new VisitorLog();
		$visitorlog->visitor_id = $visitor->id;
		$visitorlog->people_id = $person;
		$visitorlog->vehicle_id = $vehicle;
		$visitorlog->type = $type;
		$visitorlog->save();

		return $response->withJson(
			[
				'success' => true,
				'data' => $visitorlog
			]
		);
	}

	public function getUsersByUnit($request, $response, $id)
	{
		if( ! User::isLogged()) {
			header('Location: /login');
			exit;
		}
		$users = Person::where('unit_id', $id)->get();

        return $response->withJson(
        	$users
        );
	}
}
