<?php

Class OauthToken extends OauthAppModel {
	
	
	public function findTokenByUserId($service_name, $user_id) {
		
		$conditions = array(
			'OauthToken.service_name' => 'twitter',
			'OauthToken.user_id' => $response['user_id']
		);
		
		$this->recursive = -1;
		return $this->find('first', compact('conditions'));
		
	}
	
	public function findTokenByServiceUserId($service_name, $service_user_id) {
		
		$conditions = array(
			'OauthToken.service_name' => 'twitter',
			'OauthToken.service_user_id' => $response['user_id']
		);
		
		$this->recursive = -1;
		return $this->find('first', compact('conditions'));
		
	}
	
	public function save($data) {
		
		if (isset($data[ $this->alias ])) {
			
			$data = $data[ $this->alias ];
			
		}
		
		$conditions = array(
			'user_id' => $data['user_id'],
			'service_name' => $data['service_name']
		);
		
		$fields = array('id');
		
		$this->recursive = -1;
		$token = $this->find('first', compact('conditions', 'fields'));
		
		if (!empty($token)) {
			
			$this->id = $token[ $this->alias ]['id'];
			
		}
		
		return parent::save($data);		
		
	}
	
}

?>