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
			'version' => '0.5',
			'release-date' => '2011-04-05T06:20:03+00:00',
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




function generate_calendar($path, $url = '', $class = 'calendar', $mtype = 'full', $dtype = 'full'){
	$output = '';

	$q_year = 'year';
	$q_month = 'month';

	$settings = array();
	$settings["calendar_class"] = $class;
	$settings["year"] = (isset($_REQUEST[$q_year]) && preg_match('/[0-9]{4}/',$_REQUEST[$q_year]) ? $_REQUEST[$q_year] : date('Y'));
	$settings["month"] = (isset($_REQUEST[$q_month]) && preg_match('/[0-1]{1}[0-9]{1}/', $_REQUEST[$q_month]) ? $_REQUEST[$q_month] : date('m'));
	$settings["year_url_pattern"] = $settings["month_url_pattern"] = preg_replace('/\\?.*/','',$_SERVER['REQUEST_URI'])."?".$q_year."={year}&amp;".$q_month."={month}";
	$settings["url"] = $url;
	$settings["month_type"] = $mtype;
	$settings["day_type"] = $dtype;

	$calendar = new displayCalendar($path[0]->childNodes, $settings);

	$output .= $calendar->render();

	return $output;
}

// Taken from https://github.com/jibla/PHP-Calendar, Authored by Jibla (c) 2010
// Modified by Scott Tesoriere <scott@tesoriere.com> (c) 2011
class displayCalendar {

	private $settings = array();

	private $strings = array();

	private $events = array();

	public function __construct(&$events = array(), $settings = array(), $strings = array()) {
		// parse the XML
		foreach ($events as $event) {
			if (!preg_match('/entry/', $event->nodeName)) continue;
			$i = count($this->events);
			$this->events[$i]["id"] = $event->getAttribute("id");

			// parse through the fields
			foreach ($event->childNodes as $item) {
				if ($item->nodeName == '#text') continue;
				$dates = $item->getElementsByTagName("date");
				// if it's not a datetime field we don't care about it, if they have a regular date field this will crash though, need to 		check for it
				if (!$dates->length) $this->events[$i][$item->nodeName] = $item->nodeValue;
				// check through all the dates
				foreach ($dates as $date) {
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
		$this->settings['day'] = date('d');

		$this->settings['current_day_class'] = 'current';
		$this->settings['weekend_class'] = 'weekendday';
		$this->settings['calendar_class'] = 'calendar';
		$this->settings['weekdays_class'] = 'weekdays';
		$this->settings['year_class'] = 'year';
		$this->settings['month_class'] = 'month';
		$this->settings['days_class'] = 'days';
		$this->settings['month_type'] = 'full';
		$this->settings['day_type'] = 'full';

		// set the user settings and strings
		$this->settings = array_merge($this->settings, $settings);
		$this->strings = array_merge($this->strings, $strings);


	}


	public function setSettings($settings = array()) {
		$this->settings = array_merge($this->settings, $settings);
	}

	public function setStrings($strings = array()) {
		$this->strings = array_merge($this->strings, $strings);
	}

	// 1 - objects to be rendered
	public function render() {

		// php-date like string from set date parameters
		$currentDate = date("Y")."-".date("m")."-".date('d');
		// timestamp used below
		$timestamp = strtotime($this->settings['year'].'-'.$this->settings['month'].'-'.$this->settings['day']);
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

		$output .= "\t<tr><th colspan='7' class=\"today\"><a href=\"".preg_replace('/\\?.*/','',$_SERVER['REQUEST_URI'])."\">Today</a></th></tr>\n";
		$output .= "\t\t<th colspan='7' class='".$this->settings['year_class']."'>".$yearToPringString."</th>\n";
		$output .= "\t</tr>\n";

		// current month row
		$output .= "\t<tr>";

		// next and prev month story
		if (isset($this->settings['month_url_pattern'])) {

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


			$nextMonth = str_pad($nextMonth, 2, "0", STR_PAD_LEFT);
			$prevMonth = str_pad($prevMonth, 2, "0", STR_PAD_LEFT);

			$prevMonthUrl = str_replace('{year}', $prevYear, $this->settings['month_url_pattern']);
			$prevMonthUrl = str_replace('{month}', $prevMonth, $prevMonthUrl);

			$nextMonthUrl = str_replace('{year}', $nextYear, $this->settings['month_url_pattern']);
			$nextMonthUrl = str_replace('{month}', $nextMonth, $nextMonthUrl);

			$monthToPringString = "\n\t\t\t<a href='".$prevMonthUrl."'>".$this->strings['prev_month_link']."</a>\n";
			$monthToPringString .= "\t\t\t<span>".$this->strings['month'][$this->settings['month']]['full']."</span>\n";
			$monthToPringString .= "\t\t\t<a href='".$nextMonthUrl."'>".$this->strings['next_month_link']."</a>\n\t\t";

		} else {

			$monthToPringString = $this->strings['month'][$this->settings['month']][$this->settings['month_type']];

		}

		$output .= "\n\t\t<th colspan='7' class='".$this->settings['month_class']."'>".$monthToPringString."</th>\n";
		$output .= "\t</tr>\n";

		$output .= "</thead>\n";

		$output .= "<tbody>\n";

		// printing week day names
		$output .= "\t<tr class='".$this->settings['weekdays_class']."'>\n";
		for ($weekDay = 1; $weekDay <= 7; $weekDay++) {
			$output .= "\t\t<td>".$this->strings['week'][$weekDay][$this->settings['day_type']]."</td>\n";
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
				if (strtotime($currentDate) == strtotime($currentDayPrinting)) {
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
				$output .= "\t\t<td".$class."><span>".$dayToPrint."</span>";
				foreach ($list as $item) {
					$output .= "<ul>";
					foreach ($item as $k=>$v) {
						if ($k == "start" or $k == "end" or $k == "id") continue;
						$output .= "<li class=\"".$k."\">"; 
						if ($this->settings['url'] != '') 
							$output .= "<a rel=\"event\" href=\"".$this->settings["url"]."/".$item["id"]."\">".$v."</a>";
						else 
							$output .= $v;
						$output .= "</li>";
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