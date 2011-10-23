<?php

Class OauthServicesController extends OauthAppController {

	var $uses = array('Oauth.OauthToken');	
	
	function beforeFilter() {

		parent::beforeFilter();
		$this->autoRender = false;
		
	}	

	/**
	 * Initialize OAuth authentication process
	 *
	 * @param string $service 
	 * @return void
	 * @author Rui Cruz
	 */
	public function connect($service) {

		App::import('Vendor', 'HttpSocketOauth');
		$Http = new HttpSocketOauth();
		$request = array(
			'uri' => array(
				'host' => Configure::read("$service.host"),
				'path' => '/oauth/request_token',
			),
			'method' => 'GET',
			'auth' => array(
				'method' => 'OAuth',
				'oauth_callback' => Router::url(Configure::read("$service.callback_url"), true),
				'oauth_consumer_key' => Configure::read("$service.consumer_key"),
				'oauth_consumer_secret' => Configure::read("$service.consumer_secret"),
			),
		);
		
		$this->log($request, 'oauth');
		
		$response = $Http->request($request);
		
		$this->log($response, 'oauth');
		
		parse_str($response, $response);
		
		// Redirect user to twitter to authorize  my application
		$this->redirect('http://' . Configure::read("$service.host") . '/oauth/authorize?oauth_token=' . $response['oauth_token']);
		
	}
	
	/**
	 * Callback URL for the OAuth
	 *
	 * @param string $service 
	 * @return void
	 * @author Rui Cruz
	 */
	public function callback($service) {
		
		App::import('Vendor', 'HttpSocketOauth');
		$Http = new HttpSocketOauth();
		// Issue request for access token
		$request = array(
			'uri' => array(
				'host' => Configure::read("$service.host"),
				'path' => '/oauth/access_token',
			),
			'method' => 'POST',
			'auth' => array(
				'method' => 'OAuth',
				'oauth_consumer_key' => Configure::read("$service.consumer_key"),
				'oauth_consumer_secret' => Configure::read("$service.consumer_secret"),
				'oauth_token' => $this->params['url']['oauth_token'],
				'oauth_verifier' => $this->params['url']['oauth_verifier'],
			),
		);
		$response = $Http->request($request);
		parse_str($response, $response);
		
		$this->afterCallback($service, $response);
		
	}
	
	/**
	 * Called after the Service authenticates
	 *
	 * @param string $service 
	 * @return void
	 * @author Rui Cruz
	 */
	protected function afterCallback($service = null, $response = null) {

		# extend to redirect after callback is done
		#$this->log($response, 'oauth');
		
	}

}