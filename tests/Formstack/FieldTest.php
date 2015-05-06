<?php

namespace MattLeff\Tests\Formstack;

use MattLeff\Formstack\Field;

class FieldTest extends \PHPUnit_Framework_TestCase
{
	
	function testFromApi()
	{
		$details = array(
			'id' => '32385123',
			'label' => 'Appointment Title',
			'hide_label' => '0',
			'description' => '',
			'name' => 'appointment_title',
			'type' => 'text',
			'options' => '',
			'required' => '1',
			'uniq' => '0',
			'hidden' => '0',
			'readonly' => '0',
			'colspan' => '1',
			'sort' => '0',
			'default' => '',
			'text_size' => 50,
			'maxlength' => 0,
			'placeholder' => '',
		);
		$field = Field::fromApi($details);
		$this->assertEquals("32385123", $field->getId());
		$this->assertEquals("Appointment Title", $field->getLabel());
		$this->assertEquals("text", $field->getType());
	}
	
}
