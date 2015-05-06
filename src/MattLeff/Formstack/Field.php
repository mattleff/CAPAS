<?php

namespace MattLeff\Formstack;

use BadMethodCallException;
use InvalidArgumentException;
use MattLeff\Helper;
use MattLeff\Formstack\API;

class Field
{
	
	public $valid_keys = array(
		"id",
		"label",
		"hide_label",
		"description",
		"name",
		"type",
		"options",
		"options_values",
		"required",
		"uniq",
		"hidden",
		"readonly",
		"colspan",
		"sort",
		"default",
		"rows",
		"cols",
		"maxlength",
		"placeholder",
	);
	
	protected $form = null;
	protected $details = array();
	
	protected $valid_types = array(
        "text",
        "textarea",
        "name",
        "address",
        "email",
        "phone",
        "creditcard",
        "datetime",
        "file",
        "number",
        "select",
        "radio",
        "checkbox",
        "matrix",
        "richtext",
        "embed",
        "product",
        "section",
    );
	
	public static function fromApi(array $details, Form $form = null)
	{
		$field = new Field($details["type"], $details["label"]);
		if($form) {
			$field->setForm($form);
		}
		foreach($details as $key => $value) {
			if(in_array($key, $field->valid_keys)) {
				$field->setByKey($key, $value);
			}
		}
		return $field;
	}
	
	public function __construct($type, $label)
	{
		$this->details = array_fill_keys($this->valid_keys, null);
		$this->setByKey("type", $type);
		$this->setByKey("label", $label);
	}
	
	public function setForm(Form $form)
	{
		$this->form = $form;
	}
	
	public function getByKey($key)
	{
		return Helper::safeAccess($key, $this->details);
	}
	
	public function setByKey($key, $value)
	{
		if(!array_key_exists($key, $this->details)) {
			throw new InvalidArgumentException("Invalid key provided: ".$key);
		}
		//TODO: more validation around which keys can be changed and to what values
		switch($key) {
			case "type":
				if(! in_array($value, $this->valid_types)) {
					throw new InvalidArgumentException("Invalid type provided: ".$value);
				}
		}
		$this->details[$key] = $value;
		
		return $this;
	}
	
	public function save()
	{
		if(!$this->form) {
			throw new RuntimeException("This field is not associated with a form!");
		}
		$parameters = array(
			"field_type" => $this->details["type"],
			"label" => $this->details["label"],
			/*"hide_label" => "",
			"description" => "",
			"description_callout" => "",
			"default_value" => "",*/
			"options" => $this->details["options"],
			"options_values" => $this->details["options_values"],
			/*"required" => "",
			"readonly" => "",
			"hidden" => "",
			"uniq" => "",
			"colspan" => "",
			"sort" => ""*/
		);
		return API::get()->makeRequest("field/".$this->details["id"], "PUT", $parameters);
	}
	
	public function __call($name, $arguments)
	{
		if(preg_match('/get(\w+)/', $name, $matches)) {
			return $this->getByKey($this->fromCamelCase($matches[1]));
		} elseif(preg_match('/set(\w+)/', $name, $matches)) {
			return $this->setByKey($this->fromCamelCase($matches[1]), Helper::safeAccess(0, $arguments));
		} else {
			throw new BadMethodCallException();
		}
	}
	
	protected function fromCamelCase($method)
	{
		return trim(preg_replace_callback("/([A-Z])/", function($matches) {
			return "_".strtolower($matches[1]);
		}, $method), "_");
	}
	
}
