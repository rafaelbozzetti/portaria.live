<?php

namespace Rapid\Views;

use Slim\Csrf\Guard;

class CsrfExtension extends \Twig_Extension
{

	protected $guard;

	public function __construct(Guard $guard)
	{
		$this->guard = $guard;
	}

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('csrf_field', array($this, 'csrfField')),
        ];
    }

    public function csrfField()
    {
    	return '
    		<input type="hidden" name="'. $this->guard->getTokenNameKey() .'" value="'. $this->guard->getTokenName() .'" />
    		<input type="hidden" name="'. $this->guard->getTokenValueKey() .'" value="'. $this->guard->getTokenValue() .'" />

    	';
    }

}
