<?php

namespace Rapid\Controllers;

use \Rapid\Models\Unit;
use \Rapid\Models\Block;
use \Rapid\Models\Pagination;

class UnidadesController extends Controller
{
	public function index($request, $response, $args)
	{

		if( isset($_POST['btn-search']) ) {
	        $session = new \SlimSession\Helper;
	        $session->set('units_filter', filter_var($_POST['search'], FILTER_SANITIZE_STRING));
	        $session->set('units_blocks', filter_var($_POST['blocks'], FILTER_SANITIZE_STRING));
		}

		if( isset($_POST['btn-clear']) ) {
	        $session = new \SlimSession\Helper;
			$session->delete('units_filter');
		  	unset($session->units_filter);
		  	unset($session['units_filter']);

			$session->delete('units_blocks');
		  	unset($session->units_blocks);
		  	unset($session['units_blocks']);
		}

        $page = (int)( $args['page'] ? $args['page'] : 1); 

        $session = new \SlimSession\Helper;
        $units_filter = $session->get('units_filter', false);
        $units_blocks = $session->get('units_blocks', false);

        if($units_filter) {
			$units = Unit::where('name', 'like', '%' . $units_filter . '%')->paginate(20, '*', 20, ($page * 1));

        }else{
        	$units = Unit::where('id', '>', 0)->paginate(20, '*', 20, ($page * 1));
        }

        $pagination = new Pagination($units);
        $pagination = $pagination->getHtml(20, $page, 'unidades');

        $blocks = Block::all();

		return $this->view->render($response, 'unidades.twig', [
			'units' => $units,
			'units_filter' => ($units_filter ? $units_filter : false),
			'blocks' => $blocks,
			'pagination' => $pagination,
			'user_auth' => $this->user
		]);
	}

	public function add($request, $response)
	{

		$blocks = Block::all();

		return $this->view->render($response, 'unidades_add.twig', [
			'blocks' => $blocks,
			'user_auth' => $this->user
		]);
	}

	public function edit($request, $response, $id)
	{
		if($id) {
			
			$unit = Unit::find($id['id']);
			$blocks = Block::all();

			return $this->view->render($response, 'unidades_edit.twig', [
				'unit' => $unit,
				'blocks' => $blocks,
				'user_auth' => $this->user
			]);
		}

		header('Location: /unidades');
		exit;		
	}


	public function remove($request, $response, $id)
	{
		if($id) {

			$unit = Unit::find($id['id']);
			$blocks = Block::all();

			return $this->view->render($response, 'unidades_remove.twig', [
				'unit' => $unit,
				'blocks' => $blocks,
				'user_auth' => $this->user
			]);
		}

		header('Location: /unidades');
		exit;
	}


	public function confirm_remove($request, $response, $id)
	{

		$id = $request->getParam('id');

		if($id) {
			$unit = Unit::find($id);
			$unit->delete();
		}

		header('Location: /unidades');
		exit;
	}	

	public function store($request, $response, $id)
	{
		
		$id = $request->getParam('id');
		$name = filter_var($request->getParam('name'), FILTER_SANITIZE_STRING);
		$number = filter_var($request->getParam('number'), FILTER_SANITIZE_STRING);
		$block = filter_var($request->getParam('block'), FILTER_VALIDATE_INT);

		if($_POST['id']) {

			$unit = Unit::find($id);
			$unit->name = $name;
			$unit->number = $number;
			$unit->block_id = $block;
			$unit->save();

			header('Location: /unidades');
			exit;
		}

		$unit = new Unit();
		$unit->name = $name;
		$unit->number = $number;
		$unit->block_id = $block;
		$unit->save();

		header('Location: /unidades');
		exit;
	}

}
