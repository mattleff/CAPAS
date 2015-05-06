<?php

namespace MattLeff\Calendar;

use Closure;
use DateTime;
use DateInterval;
use Sabre\VObject\Reader;
use Sabre\VObject\FreeBusyGenerator;

class ICalendar implements FreeBusyInterface
{
	
	protected $icalendar = null;
	
	public function __construct($icalendar_url)
	{
		$this->icalendar = Reader::read(file_get_contents($icalendar_url));
	}
	
	public function getFreeTimes(DateTime $min_date, DateTime $max_date, DateInterval $interval, Closure $filter_closure = null)
	{
		$generator = new FreeBusyGenerator($min_date, $max_date, $this->icalendar);
		$freebusy_component = $generator->getResult()->getComponents()[0];
		
		$free_times = array();
		do {
			$interval_date = clone $min_date;
			$interval_date->add($interval);
			if($filter_closure && $filter_closure($min_date) && $freebusy_component->isFree($min_date, $interval_date)) {
				$free_times[] = $min_date;
			}
			$min_date = $interval_date;
		} while($min_date < $max_date);
		return $free_times;
	}
	
}
