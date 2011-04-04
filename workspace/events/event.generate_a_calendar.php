<?php 
require_once(TOOLKIT . '/class.event.php');

Class eventgenerate_a_calendar extends Event {
    const ROOTELEMENT = 'generate-a-calendar';
    public $eParamFILTERS = array();
    public static function about() {
        return array(
                 'name' => 'Generate a Calendar',
                 'author' => array(
                        'name' => 'Scott Tesoriere',
                        'website' => 'http://tesoriere.com',
                        'email' => 'scott@tesoriere.com'),
                 'version' => '1.0',
                 'release-date' => '2010-10-09T01:29:08+00:00',
                 'trigger-condition' => 'action[generate-a-calendar]');  
    }
    public static function documentation() {
        return '';
    }
    public function load() {
        $functions = array('generate_calendar');
        Frontend::instance()->Page()->registerPHPFunction($functions);
    }
    protected function __trigger() {
    }       
}




function generate_calendar($path){
    $output = '';
    $events = $path[0]->childNodes;
 
  	$calendar = new Calendar($events);

  	$output .= $calendar->render();
        
    return $output;
}

// Taken from https://github.com/jibla/PHP-Calendar, Authored by Jibla (c) 2010
// Modified by Scott Tesoriere <scott@tesoriere.com> (c) 2011
class Calendar {
	
	private $settings = array();
	
	private $links = array();
	
	private $strings = array();
	
	private $events = array();
	
	public function __construct(&$events = array(), $settings = array(), $strings = array(), $links = array()) {
	  foreach ($events as $event) {
      if (!preg_match('/entry/', $event->nodeName)) continue;
      $i = count($this->events);
      $data = $event->childNodes;

       foreach ($event->getElementsByTagName("date-of-event") as $item) {
         foreach ($item->getElementsByTagName("date") as $date) {
           foreach ($data as $meta) {
             if ($meta->nodeName == '#text' || $meta->nodeName == 'date-of-event') continue;
             $this->events[$i][$meta->nodeName] = $meta->nodeValue;
           }
           // start
           foreach ($date->getElementsByTagName("start") as $start) {
   
             $this->events[$i]["start"]["date"] = $start->nodeValue;
             $this->events[$i]["start"]["time"] = $start->getAttribute("time");
             $this->events[$i]["start"]["iso"] = $start->getAttribute("iso");
             $this->events[$i]["start"]["offset"] = $start->getAttribute("offset");
             $this->events[$i]["start"]["weekday"] = $start->getAttribute("weekday");
           }
           // end
           foreach ($date->getElementsByTagName("end") as $end) {
             $this->events[$i]["end"]["date"] = $end->nodeValue;
             $this->events[$i]["end"]["time"] = $end->getAttribute("time");
             $this->events[$i]["end"]["iso"] = $end->getAttribute("iso");
             $this->events[$i]["end"]["offset"] = $end->getAttribute("offset");
             $this->events[$i]["end"]["weekday"] = $end->getAttribute("weekday");
           }
           $i++;
         }
       }	    
	  }
    // foreach ($events as $event) {
    //     if (!preg_match('/entry/', $event->nodeName)) continue;
    //     // $output .= ucfirst($event->nodeName).' ';
    //     // $output .= $event->getAttribute('id');
    //     // $output .= '<br />';
    //     $data = $event->childNodes;
    //     foreach ($event->getElementsByTagName("date-of-event") as $item) {
    //       foreach ($item->getElementsByTagName("date") as $date) {
    //         if ($date->nodeName == '#text') continue;
    //         // start
    //         foreach ($date->getElementsByTagName("start") as $start) {
    //           if (isset($this->events[trim($start->nodeValue)])) {
    //             $i = count($this->events[trim($start->nodeValue)]);
    //           }
    //           else { $i = 0; }
    //           foreach ($data as $meta) {
    //             if ($meta->nodeName == '#text' || $meta->nodeName == 'date-of-event') continue;
    //             $this->events[trim($start->nodeValue)][$i][$meta->nodeName] = $meta->nodeValue;
    //           }
    // 
    //           $this->events[trim($start->nodeValue)][$i]["start"]["date"] = $start->nodeValue;
    //           $this->events[trim($start->nodeValue)][$i]["start"]["time"] = $start->getAttribute("time");
    //           $this->events[trim($start->nodeValue)][$i]["start"]["iso"] = $start->getAttribute("iso");
    //           $this->events[trim($start->nodeValue)][$i]["start"]["offset"] = $start->getAttribute("offset");
    //           $this->events[trim($start->nodeValue)][$i]["start"]["weekday"] = $start->getAttribute("weekday");
    //         }
    //         // end
    //         foreach ($date->getElementsByTagName("end") as $end) {
    //           $this->events[trim($start->nodeValue)][$i]["end"]["date"] = $end->nodeValue;
    //           $this->events[trim($start->nodeValue)][$i]["end"]["time"] = $end->getAttribute("time");
    //           $this->events[trim($start->nodeValue)][$i]["end"]["iso"] = $end->getAttribute("iso");
    //           $this->events[trim($start->nodeValue)][$i]["end"]["offset"] = $end->getAttribute("offset");
    //           $this->events[trim($start->nodeValue)][$i]["end"]["weekday"] = $end->getAttribute("weekday");
    //         }
    //       }
    //     }
    // }
    // print_r($this->events);
		
		// month names
		$this->strings['month']['01']['full'] = 'January';
		$this->strings['month']['02']['full'] = 'February';
		$this->strings['month']['03']['full'] = 'March';
		$this->strings['month']['04']['full'] = 'April';
		$this->strings['month']['05']['full'] = 'May';
		$this->strings['month']['06']['full'] = 'June';
		$this->strings['month']['07']['full'] = 'July';
		$this->strings['month']['08']['full'] = 'August';
		$this->strings['month']['09']['full'] = 'September';
		$this->strings['month']['10']['full'] = 'October';
		$this->strings['month']['11']['full'] = 'November';
		$this->strings['month']['12']['full'] = 'December';
		
		$this->strings['month']['01']['short'] = 'Jan';
		$this->strings['month']['02']['short'] = 'Feb';
		$this->strings['month']['03']['short'] = 'Mar';
		$this->strings['month']['04']['short'] = 'Apr';
		$this->strings['month']['05']['short'] = 'May';
		$this->strings['month']['06']['short'] = 'Jun';
		$this->strings['month']['07']['short'] = 'Jul';
		$this->strings['month']['08']['short'] = 'Aug';
		$this->strings['month']['09']['short'] = 'Sep';
		$this->strings['month']['10']['short'] = 'Oct';
		$this->strings['month']['11']['short'] = 'Nov';
		$this->strings['month']['12']['short'] = 'Dec';
		
		// weekday names
		$this->strings['week']['1']['full'] = 'Monday';
		$this->strings['week']['2']['full'] = 'Tuesday';
		$this->strings['week']['3']['full'] = 'Wednesday';
		$this->strings['week']['4']['full'] = 'Thursday';
		$this->strings['week']['5']['full'] = 'Friday';
		$this->strings['week']['6']['full'] = 'Saturday';
		$this->strings['week']['7']['full'] = 'Sunday';
		
		$this->strings['week']['1']['short'] = 'Mon';
		$this->strings['week']['2']['short'] = 'Tue';
		$this->strings['week']['3']['short'] = 'Wedn';
		$this->strings['week']['4']['short'] = 'Thurs';
		$this->strings['week']['5']['short'] = 'Fri';
		$this->strings['week']['6']['short'] = 'Sat';
		$this->strings['week']['7']['short'] = 'Sun';
		
		// next and prev year string
		$this->strings['next_year_link'] = '&rarr;';
		$this->strings['prev_year_link'] = '&larr;';
		
		// next and prev month string
		$this->strings['next_month_link'] = '&rarr;';
		$this->strings['prev_month_link'] = '&larr;';
		
		// settings
		$this->settings['year'] = date('Y');
		$this->settings['month'] = date('m');
		$this->settings['day'] = date('j');
		
		$this->settings['current_day_class'] = 'current';
		$this->settings['weekend_class'] = 'weekendday';
		$this->settings['calendar_class'] = 'calendar';
		$this->settings['weekdays_class'] = 'weekdays';
		$this->settings['year_class'] = 'year';
		$this->settings['month_class'] = 'month';
		$this->settings['days_class'] = 'days';
		
		// set the user settings and strings
		$this->settings = array_merge($this->settings, $settings);
		$this->strings = array_merge($this->strings, $strings);
		
		$this->links = $links;
		
		
	}
	
	
	public function setSettings($settings = array()) {
		$this->settings = array_merge($this->settings, $settings);
	}
	
	public function setStrings($strings = array()) {
		$this->strings = array_merge($this->strings, $strings);
	}
	
	public function setLinks($links = array()) {
		$this->links = array_merge($this->links, $links);
	}
	
	
	// 1 - objects to be rendered
	public function render() {
	
		// php-date like string from set date parameters
		$currentDate = $this->settings['year']."-".$this->settings['month']."-".$this->settings['day'];
		// timestamp for $currentDate
		$timestamp = strtotime($currentDate);
		// amount of days in the current month
		$amountOfDays = date('t', $timestamp);
		// first week day in the month
		$firstWeekDay = date('N', strtotime($this->settings['year'].'-'.$this->settings['month'].'-01'));
		$currentWeekDay = $firstWeekDay - 1;

		
		// starting to generate output html
		$output = "<table class='".$this->settings['calendar_class']."'>\n";
		
		$output .= "<thead>\n";
		
		// current year row
		$output .= "\t<tr>\n";
		
		// next and prev year story
		if (isset($this->settings['year_url_pattern'])) {
			
			$prevYearUrl = str_replace('{year}', $this->settings['year'] - 1, $this->settings['year_url_pattern']);
			$prevYearUrl = str_replace('{month}', $this->settings['month'], $prevYearUrl);
			
			$nextYearUrl = str_replace('{year}', $this->settings['year'] + 1, $this->settings['year_url_pattern']);
			$nextYearUrl = str_replace('{month}', $this->settings['month'], $nextYearUrl);
			
			$yearToPringString = "\n\t\t\t<a href='".$prevYearUrl."'>".$this->strings['prev_year_link']."</a>\n";
			$yearToPringString .= "\t\t\t<span>".$this->settings['year']."</span>\n";
			$yearToPringString .= "\t\t\t<a href='".$nextYearUrl."'>".$this->strings['next_year_link']."</a>\n\t\t";
			
		} else {
			
			$yearToPringString = $this->settings['year'];
			
		}
		
		$output .= "\t\t<th colspan='7' class='".$this->settings['year_class']."'>".$yearToPringString."</th>\n";
		$output .= "\t</tr>\n";
		
		// current month row
		$output .= "\t<tr>";
		
		// next and prev month story
		if (isset($this->settings['year_url_pattern'])) {
			
			$nextMonth = $this->settings['month'] + 1;
			if ($nextMonth > 12) {
				
				$nextMonth = 1;
				$nextYear = $this->settings['year'] + 1;
				
			} else {
				
				$nextYear = $this->settings['year'];
				
			}
			
			
			$prevMonth = $this->settings['month'] - 1;
			if ($prevMonth < 1) {
				
				$prevMonth = 12;
				$prevYear = $this->settings['year'] - 1;
				
			} else {
				
				$prevYear = $this->settings['year'];
				
			}
			
			
			$prevMonthUrl = str_replace('{year}', $prevYear, $this->settings['month_url_pattern']);
			$prevMonthUrl = str_replace('{month}', $prevMonth, $prevMonthUrl);
			
			$nextMonthUrl = str_replace('{year}', $nextYear, $this->settings['month_url_pattern']);
			$nextMonthUrl = str_replace('{month}', $nextMonth, $nextMonthUrl);
			
			$monthToPringString = "\n\t\t\t<a href='".$prevMonthUrl."'>".$this->strings['prev_month_link']."</a>\n";
			$monthToPringString .= "\t\t\t<span>".$this->strings['month'][$this->settings['month']]['full']."</span>\n";
			$monthToPringString .= "\t\t\t<a href='".$nextMonthUrl."'>".$this->strings['next_month_link']."</a>\n\t\t";
			
		} else {
			
			$monthToPringString = $this->strings['month'][$this->settings['month']]['full'];
			
		}
		
		$output .= "\n\t\t<th colspan='7' class='".$this->settings['month_class']."'>".$monthToPringString."</th>\n";
		$output .= "\t</tr>\n";
		
		$output .= "</thead>\n";
		
		$output .= "<tbody>\n";
		
		// printing week day names
		$output .= "\t<tr class='".$this->settings['weekdays_class']."'>\n";
		for ($weekDay = 1; $weekDay <= 7; $weekDay++) {
			$output .= "\t\t<td>".$this->strings['week'][$weekDay]['short']."</td>\n";
		}
		$output .= "\t</tr>\n";
		
		// printing days itself
		$output .= "\t<tr class='".$this->settings['days_class']."'>\n";
		$dayToPrint = 0;
		for ($day = 1; $day <= $amountOfDays + ($firstWeekDay-1); $day++) {
			
			// defining we should start counting and printing days or not (depending on $firstWeekDay)
			if ($day >= $firstWeekDay) {
				$dayToPrint++;
				$currentWeekDay++;
				if ($currentWeekDay > 7) $currentWeekDay = 1;
			}
			
			// if we are under the "Monday", create another row for this week
			if ($day%7 == 1 && $day != 1) {
				$output .= "\t<tr class='".$this->settings['days_class']."'>\n";
			}
			
			// if dayToPrint isnt zero, so it will be good if we print start to print days :D
			if ($dayToPrint != 0) {
				
				$class = '';
				
				// php date like string for the day printing now
				$currentDayPrinting = $this->settings['year'].'-'.$this->settings['month'].'-'.$dayToPrint;
				
				// if day is current
				if ($currentDate == $currentDayPrinting) {
					$class .= $this->settings['current_day_class'];
				}
				
				// if day if weekend day
				if ($currentWeekDay > 5) {
					if ($class == '') {
						$class .= $this->settings['weekend_class'];
					} else {
						$class .= ' '.$this->settings['weekend_class'];
					}
					
				}
				
				// finishing to create $class variable
				if ($class != '') {
					$class = " class='".$class."'";
				}
				
				$list = array();
				// check if we have an event on this day
				if (count($this->events)) {
				  foreach ($this->events as $event) {
				    if (strtotime($event["start"]["date"]) == strtotime($currentDayPrinting) || (isset($event["end"]) && 
				        ((strtotime($event["start"]["date"]) < strtotime($currentDayPrinting)) && (strtotime($currentDayPrinting) < strtotime($event["end"]["date"]))))) {
				          $list[] = $event;
				        }
				  }
				}
				
				// finally print the day
				$output .= "\t\t<td".$class.">".$dayToPrint;
				foreach ($list as $item) {
			    $output .= "<ul>";
				  foreach ($item as $k=>$v) {
				    if ($k == "start" or $k == "end") continue;
            $output .= "<li class=\"".$k."\">".$v."</li>";
				  }
				  $output .= "</ul>";
				}
				$output .= "</td>\n";	
				
			} else {
				$output .= "\t\t<td></td>";	
			}
			
			// if we are under the "Sunday", just jump on the another line
			if ($day%7 == 0) {
				$output .= "\t</tr>\n";
			}
			
		}
		
		// close everything we have to
		$output .= "\t</tr>\n";
		$output .= "</tbody>\n";
		$output .= "</table>\n";
		
		// return the result
		return $output;
		
	}

	
}

?>