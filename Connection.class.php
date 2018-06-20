<?php
/**
 * ISPAPI
 * Copyright © 1API
 */

namespace ISPAPI;

/**
 * Function connect
 * Returns a Connection object connected to the API Server (URL, ENTITY, LOGIN, PASSWORD are mandatory to connect the server, ROLE ans USER are optional)
 *
 * @param array $params The credentials for the connection
 * @throws Exception Throws exception when credentials missing
 * @return \ISPAPI\Connection A connection to the API Server
 */
function connect($params){
	if(!isset($params)){
		throw new Exception('Credentials missing');
	}
	return new Connection($params);
}


/**
 * ISPAPI Connection
 *
 * @package ISPAPI
 */
class Connection{

	/**
	 * The URL of the API Server
	 * @var string
	 */
	private $url;
	
	/**
	 * The entity string for the connection
	 * 
	 * OT&E = 1234
	 * LIVE = 54cd
	 *
	 * @var string
	 */
	private $entity;
	
	/**
	 * The login for the connection
	 * @var string
	 */
	private $login;
	
	/**
	 * The password for the connection
	 * @var string
	 */
	private $password;
	
	/**
	 * The sub user for the connection
	 * @var string
	 */
	private $user;
	
	/**
	 * The role user for the connection
	 * @var string
	 */
	private $role;
	
	/**
	 * Constructor
	 * 
	 * @param array $params An array of credentials for the connection
	 */
	function __construct($params){
		$this->url = $params["url"];
		$this->entity = $params["entity"];
		$this->login = $params["login"];
		$this->password = $params["password"];
		if(isset($params["user"])){
			$this->user = $params["user"];
		}else{
			$this->user = "";
		}
		if(isset($params["role"])){
			$this->role = $params["role"];
		}else{
			$this->role = "";
		}
	}
	
	/**
	 * Make a curl API call over HTTP(S) and returns the response as a string
	 * 
	 * @param array $command The command array
	 * @param array|string $config The config array
	 * @throws Exception Throws exception if can't init curl
	 */
	function call_raw_http($command, $config=null){
		$args = array();
		if(!empty($this->role)){
			$args["s_login"] = $this->login."!".$this->role;
		}else{
			$args["s_login"] = $this->login;
		}

		if(!empty($this->user)){
			$args["s_user"] = $this->user;
		}
		
		$args["s_pw"] = $this->password;
		$args["s_entity"] = $this->entity;
		
    	if(is_array($config)){
    		$config = Util::command_encode($config);
    	}
    	$command = Util::command_encode($command);
    	
    	$args["s_command"] = $command.$config;
    	
    	$curl = curl_init($this->url);
    	if($curl === false){
    		throw new Exception('API access error: curl_init failed');
    	}

    	curl_setopt( $curl, CURLOPT_POST, 1 );
    	curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query($args) );
    	curl_setopt( $curl, CURLOPT_HEADER, 0 );
    	curl_setopt( $curl, CURLOPT_RETURNTRANSFER , 1 );
    	curl_setopt( $curl, CURLOPT_HTTPHEADER, array('Expect:') );
    	
    	$response = curl_exec($curl);
    	return $response;
	}
	
	/**
	 * Make a curl API call and returns the response as a string 
	 * 
	 * @param array $command The command array
	 * @param array|string $config The config array or if given as string, the s_user
	 */
	function call_raw($command, $config=null){
		return $this->call_raw_http($command, $config);
	}
	
	/**
	 * Make a curl API call and returns the response as a response object
	 * 
	 * @param array $command The command array
	 * @param array|string $config The config array or if given as string, the s_user
	 */
	function call($command, $config=null){
		$response = $this->call_raw($command, $config);
		return new Response($response);
	}
	
}
?>