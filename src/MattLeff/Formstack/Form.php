<?php

namespace MattLeff\Formstack;

use Closure;
use MattLeff\Formstack\API;
use MattLeff\Formstack\Field;

class Form
{
	
	protected $form_id = null;
	protected $fields = null;
	
	public function __construct($form_id = null)
	{
		$this->form_id = $form_id;
		if($form_id) {
			$this->fields = array_map(function($field_array) {
				return Field::fromApi($field_array, $this);
			}, API::get()->makeRequest("form/$form_id")["fields"] );
		}
	}
	
	public function getFields()
	{
		return $this->fields;
	}
	
	public function findField(Closure $finder)
	{
		return $finder( $this->getFields() );
	}
	
}
