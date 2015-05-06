<?php

namespace MattLeff;

use DateTime;
use DateInterval;
use MattLeff\Formstack\Form;
use MattLeff\Calendar\FreeBusyInterface;

class FormstackCalendarUpdater
{
	
	protected $calendar = null;
	protected $calendar_config = null;
	
	public function __construct(FreeBusyInterface $calendar, array $calendar_config)
	{
		$this->calendar = $calendar;
		$this->calendar_config = $calendar_config;
	}
	
	public function updateForm(Form $form)
	{
		$field = $this->getFormField($form);
		$options = $this->formatTimeOptions($this->getFreeTimeOptions());
		$field->setOptions(array_values($options))
			->setOptionsValues(array_keys($options))
			->save();
	}
	
	protected function getFormField(Form $form)
	{
		//TODO: use more flexible method of finding field
		return $form->findField(function($fields) {
			foreach($fields as $field) {
				if($field->getType() === "select") {
					return $field;
				}
			}
		});
	}
	
	protected function getFreeTimeOptions()
	{
		$start_hour = $this->calendar_config["start_hour"];
		$end_hour = $this->calendar_config["end_hour"];
		list($min_date, $max_date) = $this->getDateRange($this->calendar_config["schedule_days_out"]);
		$interval = new DateInterval("PT1H");
		
		$days_available = $this->calendar_config["include_weekends"]
			? array(0, 1, 2, 3, 4, 5, 6)
			: array(1, 2, 3, 4, 5);
		
		$free_times = $this->calendar->getFreeTimes($min_date, $max_date, $interval, function(DateTime $time) use($start_hour, $end_hour, $interval, $days_available) {
			if(! in_array($time->format("w"), $days_available)) return false;
			
			$end_time = clone $time;
			$end_time->setTime($end_hour, 0);
			$end_time->sub($interval);
			return $time->format("G") >= $start_hour
				&& $time->format("G") <= $end_time->format("G");
		});
		return $free_times;
	}
	
	protected function getDateRange($schedule_days_out)
	{
		$min_date = new DateTime();
		$min_date->setTime(date("H")+1, 0, 0);
		$max_date = clone $min_date;
		$max_date->modify("+$schedule_days_out days");
		return array($min_date, $max_date);
	}
	
	protected function formatTimeOptions(array $times)
	{
		$options = array();
		foreach($times as $time) {
			$options[$time->format("Y-m-d H:i:s")] = $time->format("F j - ga");
		}
		return $options;
	}
	
}
