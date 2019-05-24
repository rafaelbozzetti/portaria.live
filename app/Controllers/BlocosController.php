<?php

namespace Rapid\Controllers;

use \Rapid\Models\Block;
use \Rapid\Models\Pagination;

class BlocosController extends Controller
{

	public function index($request, $response, $args)
	{

		if( isset($_POST['btn-search']) ) {
	        $session = new \SlimSession\Helper;
	        $session->set('blocks_filter', filter_var($_POST['search'], FILTER_SANITIZE_STRING));
		}

		if( isset($_POST['btn-clear']) ) {
	        $session = new \SlimSession\Helper;
			$session->delete('blocks_filter');
		  	unset($session->blocks_filter);
		  	unset($session['blocks_filter']);
		}

        $page = (int)( $args['page'] ? $args['page'] : 1); 

        $session = new \SlimSession\Helper;
        $blocks_filter = $session->get('blocks_filter', false);

        if($blocks_filter) {
			$blocks = Block::where('name', 'like', '%' . $blocks_filter . '%')->paginate(20, '*', 20, ($page * 1));

        }else{
        	$blocks = Block::where('id', '>', 0)->paginate(20, '*', 2, ($page * 1));
        }

        $pagination = new Pagination($blocks);
        $pagination = $pagination->getHtml(20, $page, 'blocos');

		return $this->view->render($response, 'blocos.twig', [
			'blocks' => $blocks,
			'blocks_filter' => ($blocks_filter ? $blocks_filter : false),
			'pagination' => $pagination,
			'user_auth' => $this->user
		]);
	}


	public function add($request, $response)
	{
		return $this->view->render($response, 'blocos_add.twig', [
				'user_auth' => $this->user
		]);
	}


	public function edit($request, $response, $id)
	{
		if($id) {
			
			$block = Block::find($id['id']);

			return $this->view->render($response, 'blocos_edit.twig', [
				'block' => $block,
				'user_auth' => $this->user
			]);
		}

		header('Location: /blocos');
		exit;		
	}


	public function remove($request, $response, $id)
	{
		if($id) {

			$block = Block::find($id['id']);

			return $this->view->render($response, 'blocos_remove.twig', [
				'block' => $block,
				'user_auth' => $this->user
			]);
		}

		header('Location: /blocos');
		exit;
	}


	public function confirm_remove($request, $response, $id)
	{

		$id = $request->getParam('id');

		if($id) {
			$block = Block::find($id);
			$block->delete();
		}

		header('Location: /blocos');
		exit;
	}


	public function store($request, $response, $id)
	{
		
		$id = $request->getParam('id');
		$name = filter_var($request->getParam('name'), FILTER_SANITIZE_STRING);
		
		if($_POST['id']) {

			$id = filter_var($id, FILTER_VALIDATE_INT);
			
			$block = Block::find($id);
			$block->name = $name;
			$block->save();

			header('Location: /blocos');
			exit;
		}

		$block = new Block();
		$block->name = $name;
		$block->save();

		header('Location: /blocos');
		exit;
	}

}
