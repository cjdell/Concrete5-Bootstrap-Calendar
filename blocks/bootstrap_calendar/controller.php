<?php

class BootstrapCalendarBlockController extends BlockController
{
	var $pobj;

	protected $btDescription = "Bootstrap Calendar";
	protected $btName = "bootstrap_calendar";
	protected $btTable = 'btBootstrapCalendar';
	protected $btInterfaceWidth = "750";
	protected $btInterfaceHeight = "400";
	
    public function getBlockTypeName()
    {
        return t("Bootstrap Calendar");
    }
	
	function action_get_events()
	{
		$nh = Loader::helper('navigation');
	
        $from = $_POST['from'];
        $to = $_POST['to'];
    
		$response = array();
		$response['success'] = 1;
		$response['result'] = array();
		
		$pl = new PageList();
		$pl->filterByCollectionTypeHandle('calendar_event');
		
		$pages = $pl->get();
		
		$allEvents = array();
		
		foreach ($pages as $page)
		{
			$events = $this->getEventOccurancesWithinRange($from, $to, $page);
            
			$allEvents = array_merge($allEvents, $events);
		}
		
		$response['result'] = $allEvents;
		
		echo json_encode($response);
		exit;
	}
    
    private function getEventOccurancesWithinRange($from, $to, $page)
    {
		$matches = array();
	
        $nh = Loader::helper('navigation');
        
        $json = $page->getAttribute('event_time_definition_data');
        
        $fromDate = new DateTime();
		$fromDate->setTimestamp($from / 1000);
		
		$toDate = new DateTime();
		$toDate->setTimestamp($to / 1000);
        
        $event = array();
    		
		$event['id'] = $page->getCollectionID();
		$event['title'] = $page->getCollectionName();
		$event['url'] = $nh->getCollectionURL($page);
		$event['class'] = 'event-warning';
        
        $def = json_decode($json);
        
		// Date of first occurance
        $startDate = DateTime::createFromFormat('Y-m-d H:i', $def->start->date . ' ' . str_pad($def->start->time->hour,2,'0',STR_PAD_LEFT) . ':' . str_pad($def->start->time->minute,2,'0',STR_PAD_LEFT));
        
        $duration = $def->duration->value;
        
        if ($def->duration->unit == 'hours')
        {
            $duration *= 60 * 60 * 1000;
        }
        
        if ($def->recurTypeSelected == 'single')
        {
			$start = $startDate->format('U') * 1000;
			$end = $start + $duration;
			
			if ($start >= $from && $start <= $to || $end >= $from && $end <= $to)
            {        
                $event['start'] = $start;
                $event['end']   = $end;
                
                $matches[] = $event;
            }
        }
        
        if ($def->recurTypeSelected == 'weekly')
        {
            $weekDays = array();
			
			foreach ($def->recurTypes->weekly->daysOfWeek as $dayOfWeek)
			{
				if ($dayOfWeek->checked) $weekDays[] = $dayOfWeek->name;
			}
			
			$dateCounter = clone $fromDate;
			$dateCounter->setTime($startDate->format('H'), $startDate->format('i'), $startDate->format('s'));
			
			for ($dateCounter; $dateCounter < $toDate; $dateCounter->add(new DateInterval('P1D')))
			{
				if ($dateCounter < $startDate) continue;
				if (!in_array($dateCounter->format('l'), $weekDays)) continue;
				
				$occuranceStart = $dateCounter->format('U') * 1000;
						
				$eventOccurance = $event;
				
				$eventOccurance['start'] = $occuranceStart;
				$eventOccurance['end']   = $occuranceStart + $duration;
				
				$matches[] = $eventOccurance;
			}
        }
        
        if ($def->recurTypeSelected == 'monthly')
        {
			if ($def->recurTypes->monthly->mode == 'dayOfMonth')
			{
				$dayOfMonth = $startDate->format('d');
				
				$dateCounter = clone $fromDate;
				$dateCounter->setTime($startDate->format('H'), $startDate->format('i'), $startDate->format('s'));
				
				for ($dateCounter; $dateCounter < $toDate; $dateCounter->add(new DateInterval('P1D')))
				{
					if ($dateCounter < $startDate) continue;
					if ($dateCounter->format('d') != $dayOfMonth) continue;
					
					$occuranceStart = $dateCounter->format('U') * 1000;
						
					$eventOccurance = $event;
					
					$eventOccurance['start'] = $occuranceStart;
					$eventOccurance['end']   = $occuranceStart + $duration;
					
					$matches[] = $eventOccurance;
				}
			}
			
			if ($def->recurTypes->monthly->mode == 'nthWeekdayOfMonth')
			{
				$weekday = $startDate->format('l');
				$nth = $this->getNthWeekdayOfMonth($startDate);
				
				$dateCounter = clone $fromDate;				
				$dateCounter->setTime($startDate->format('H'), $startDate->format('i'), $startDate->format('s'));
				
				for ($dateCounter; $dateCounter < $toDate; $dateCounter->add(new DateInterval('P1D')))
				{
					if ($dateCounter < $startDate) continue;
					if ($dateCounter->format('l') != $weekday) continue;
					
					if ($this->getNthWeekdayOfMonth($dateCounter) == $nth)
					{
						$occuranceStart = $dateCounter->format('U') * 1000;
						
						$eventOccurance = $event;
						
						$eventOccurance['start'] = $occuranceStart;
						$eventOccurance['end']   = $occuranceStart + $duration;
						
						$matches[] = $eventOccurance;
					}
				}
			}
        }
        
        return $matches;
    }
	
	private function getNthWeekdayOfMonth($_date)
	{
		$date = clone $_date;
		$month = $date->format('m');
		
		$i = 0;
		
		for ($i = 0; $i < 5; $i++)
		{
			if ($date->format('m') != $month) break;
			$date->sub(new DateInterval('P7D'));
		}
		
		return $i;
	}
    
	public function add()
	{
		return $this->edit();
	}
	
	public function edit()
	{
		
	}
    
    public function validate($args)
    {
        
    }
    
    public function save($args)
    {
        parent::save($args);
    }

	public function view()
	{
        
	}
}

?>
