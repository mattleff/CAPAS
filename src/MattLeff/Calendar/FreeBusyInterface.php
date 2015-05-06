<?php

namespace MattLeff\Calendar;

use Closure;
use DateTime;
use DateInterval;

interface FreeBusyInterface
{
	
	public function getFreeTimes(DateTime $min_date, DateTime $max_date, DateInterval $interval, Closure $filter_closure = null);
	
}
