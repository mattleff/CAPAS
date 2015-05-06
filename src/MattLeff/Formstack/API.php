<?php

namespace MattLeff\Formstack;

use RuntimeException;
use GuzzleHttp\Client;
use MattLeff\Formstack\Form;

class API
{
	
	protected static $api = null;
	protected static $access_token = null;
	
	protected $client = null;
	
	public static function get()
	{
		if(!self::$api) {
			if(!self::$access_token) {
				throw new RuntimeException("Access token has not been set!");
			}
			self::$api = new API("v2");
		}
		return self::$api;
	}
	
	public static function setAccessToken($access_token)
	{
		self::$access_token = $access_token;
	}
	
	public function __construct($api_version)
	{
		$this->client = new Client(array(
			"base_url" => "https://www.formstack.com/api/{$api_version}/",
			"defaults" => array(
				"headers" => array(
					"Content-Type" => "application/json",
					"Authorization" => "Bearer ".self::$access_token,
				),
			),
		));
	}
	
	public function getForm($form_id)
	{
		return new Form($form_id);
	}
	
	public function makeRequest($route, $method = "GET", $parameters = array())
	{
		$request = $this->client->createRequest($method, $route, array("body" => $parameters));
		$response = $this->client->send($request);
		return $response->json();
	}
	
}
