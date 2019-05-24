<?php

namespace Rapid\Controllers;

use Rapid\Models\User;

class Controller
{

	protected $container;

	protected $user;

	public function __construct($container)
	{
		$this->container = $container;
		
        $session = new \SlimSession\Helper;
        $logged = $session->get('user_logged_session', $email);

        $this->user = $logged;

		if( ! User::isLogged()) {

			header('Location: /login');
			exit;
		}

		if( $this->user['type'] == 2) {
			header('Location: /');
			exit;
		}

	}

	public function __get($property)
	{
		if ($this->container->{$property}) {
			return $this->container->{$property};
		}
	}

}
