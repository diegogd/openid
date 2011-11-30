<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Demo_OpenID extends Controller_Demo {

	public function demo_login()
	{
		$openid = OpenID::factory();

		// Overload the return URL
		$openid->returnUrl = $this->request->url(TRUE);

		if ( ! $openid->mode)
		{
			if ($this->request->method() === Request::POST)
			{
				$identity = $this->request->post('identity');

				// Set the identity URL
				$openid->identity = $identity;

				// Request email address and full name
				$openid->required = array('namePerson/first', 'namePerson/last', 'contact/email');

				// Redirect to provider for login
				$this->request->redirect($openid->authUrl());
			}
			else
			{
				$this->content = View::factory('demo/form')
					->set('message', 'Enter your identity URL.')
					->set('inputs', array(
						'Identity URL' => Form::input('identity'),
					))
					;
			}
		}
		elseif ($openid->mode === 'cancel')
		{
			$this->content = Debug::vars('User canceled authentication.');
		}
		else
		{
			// Login finished
			$this->content = Debug::vars('Authenticated:', $openid->validate(), 'Attributes:', $openid->getAttributes());
		}
	}

}
