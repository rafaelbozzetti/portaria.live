<?php

namespace Rapid\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	
	protected $table = 'users';

    protected $fillable = [
        'email',
        'password'
    ];

    function checkLogin($username, $password) 
    {

    	if(User::where('email', $username)->count()) {

    		$password = sha1($password);

    		$valid_pass = User::where('password', $password)
                                ->where('email', $username)
                                ->count();

    		if($valid_pass == 1) {

                $user = User::where('email', $username)->first();

		        $session = new \SlimSession\Helper;
		        $session->set('user_logged_session', [
                    'email' => $user->email,
                    'name' => $user->name,
                    'type' => $user->type
                ]);
		        return true;
    		}

    		return false;
    	}

    	return false;    	
    }

    function isLogged()
    {
        $session = new \SlimSession\Helper;
        $logged = $session->get('user_logged_session', $email);

        if($logged) {
        	return true;
        }

        return false;
    }

    function logout()
    {
        $session = new \SlimSession\Helper;
		$logged = $session->get('user_logged_session', $email);
		$session->delete('user_logged_session');
	  	unset($session->user_logged_session);
	  	unset($session['user_logged_session']);    	
	  	return true;
    }

}