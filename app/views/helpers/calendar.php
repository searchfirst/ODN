<?php
/**
 * Provides calendar capabilites
 *
 **/

class CalendarHelper extends Helper
{
    var $helpers = array('Html', 'Date', 'Ajax');

    var $dateValue; // holds the date as sent
    var $dateType;  // holds the type of date sent
    var $default;   // holds the default date
    var $url = NULL;
    var $eventUrl;
    var $pf = "%s:%s";  // can be used to create other param=>val formats
    var $ajaxTarget = NULL;
    var $presentAs = 'span';
    // values that can be changed in the view
    var $monthFormat = NULL;       // example: 'F Y'
    var $weekFormat = NULL;    // example: 'wk %2$u'
    var $dayFormat = 'j';
    var $hourFormat = 'g';
    var $timeFormat = 'H:i';
    // private values
    var $__events = array();
    var $__highlights = array();

    /**
     * A one-stop place to send all of the necessary values
     * and set any defaults.  CALL THIS FIRST
     *
     * @param array $params - contains all necessary params
     * @param array $events - a variable number of event arrays accepted
     *
     * @return the current date value as passed, FALSE on failure
     *
     **/
    function establish($params = array())
    {
        // start off by setting defaults that can be overwritten
        $this->default = $this->Date->date();
        $this->dateValue = $this->default;
        $this->dateType = 'date';

        // now if any params were sent set the proper values
        if (isset($params))
        {
            foreach ($params as $key=>$val)
            {
                switch ($key)
                {
                    // set only expected values
                    case 'url':
                    case 'eventUrl':
                    case 'ajaxTarget':
                    case 'monthFormat':
                    case 'weekFormat':
                    case 'presentAs':
                    case 'default':
                    case 'dateValue':
                    case 'dateType':
                    case 'pf':
                        if (!is_null($val))
                        {
                            $this->$key = $val;
                        }
                        break;
                }
            }
        }
        // if any events were sent, set them now
        if (func_num_args() > 1)
        {
            for ($i = 1; $i < func_num_args(); $i++)
            {
                $this->events(func_get_arg($i));
            }
        }
        // we should highlight today
        $this->highlight('today', $this->Date->date());
        // make sure at least date is highlighted
        $this->highlight('selected', $this->dateValue);

        return $this->dateValue;
    }

    /**
     * Add a style to a specific date. ex: 'today' or 'active'
     *
     * @param string $label - style class label
     * @param string $mark - date to receive style
     * @return NULL
     *
     **/
    function highlight($label, $mark)
    {
        $array =& $this->__highlights[$mark];
        $array[] = $label;
        $array = array_unique($array);
    }

    /**
     * Add an event to the calendar.
     *
     * @param string $datetime - time of start of event
     * @param string $title - short description
     * @param string $id - an optional event id
     * @param string $description - long description
     * @param string $style - a style to apply as a highlight
     * @return NULL
     *
     **/
    function event( $datetime, $title, $id = NULL,
                      $description = NULL, $end = NULL, $style = NULL)
    {
        // grab just the date
        $date = $this->Date->date($datetime);
        // if the datetime is just the date, then it is an
        // all-day event.  Mark it so. NOTE: there is no end time
        if ($date == $datetime)
        {
            $this->__events[$date][$date][] = array(
                'title'=>$title,
                'id'=>$id,
                'description'=>$description,
                'style'=>$style );
            // also add the style to each layer of the event
            if (!is_null($style))
            {
                $this->highlight($style, $date);
            }
        }
        else
        {
        // otherwise assume it's a time'd event
            $hour = "$date ".$this->Date->datetime($datetime, 'H');

            $this->__events[$date][$hour][$datetime][] = array(
                'title'=>$title,
                'id'=>$id,
                'description'=>$description,
                'end'=>$end,
                'style'=>$style );
            // also add the style to each layer of the event
            if (!is_null($style))
            {
                $this->highlight($style, $date);
                $this->highlight($style, $hour);
                $this->highlight($style, $datetime);
            }
        }
    }

    /**
     * Add multiple events to the calendar.
     * Useful for database results.
     *
     * @param array $events - a collection of entries to pass to event()
     * @return NULL
     *
     **/
    function events($events)
    {
        // set default field labels
        $timelabel = 'time';
        $endlabel = 'endtime';
        $titllabel = 'title';
        $idlabel   = 'id';
        $desclabel = 'description';
        // override labels if mapping set
        if (isset($events['fieldmap']['time']))
        {
            $timelabel = $events['fieldmap']['time'];
        }
        if (isset($events['fieldmap']['endtime']))
        {
            $endlabel = $events['fieldmap']['time'];
        }
        if (isset($events['fieldmap']['title']))
        {
            $titllabel = $events['fieldmap']['title'];
        }
        if (isset($events['fieldmap']['id']))
        {
            $idlabel = $events['fieldmap']['id'];
        }
        if (isset($events['fieldmap']['description']))
        {
            $desclabel = $events['fieldmap']['description'];
        }
        // remove the fieldmap so that we can walk the rest of the array
        unset($events['fieldmap']);
        foreach ($events as $style=>$eventlist)
        {
            // if $style is not a string, let's not set a style here
            if (!is_string($style))
            {
                $style = NULL;
            }
            // begin entering records
            foreach ($eventlist as $entry)
            {
                foreach ($entry as $event)
                {
                    $time = $event[$timelabel];
                    $title = $event[$titllabel];
                    if (isset($event[$endlabel]))
                    {
                        $end = $event[$endlabel];
                    }
                    else
                    {
                        $end = NULL;
                    }
                    if (isset($event[$idlabel]))
                    {
                        $id = $event[$idlabel];
                    }
                    else
                    {
                        $id = NULL;
                    }
                    if (isset($event[$desclabel]))
                    {
                        $desc = $event[$desclabel];
                    }
                    else
                    {
                        $desc = NULL;
                    }
                    $this->event($time, $title, $id, $desc, $end, $style);
                }
            }
        }
    }

    /**
     * Return a calendar with links set on event days
     * Useful for small calendars
     *
     * @param string $datetime - the month, or a week/day from the month
     * @param int $offset - number of months from $datetime to offset
     * @param string $dayContents - method of day output
     * @return a string containing an html month calendar
     *
     **/
    function month($datetime = NULL, $offset = NULL, $dayContents = NULL)
    {
        $class = 'month';

        // if no datetime is specified, use the selected value
        if (is_null($datetime))
        {
            $datetime = $this->dateValue;
        }

        // make sure it's in the proper format
        if ($this->dateType == 'week')
        {
            $month = $this->Date->monthOfWeek($datetime, 'Y-m');
        }
        else
        {
            $month = $this->Date->date($datetime, 'Y-m');
        }

        // if there's an offset, use it
        if ($offset)
        {
            $offset = sprintf('%+d months', $offset);
            $month = $this->Date->adjustDate($month, $offset, 'Y-m');
        }
        // if it's got highlighting, do that now
        if (isset($this->__highlights[$month]))
        {
            $class .= " ".implode(" ", $this->__highlights[$month]);
        }
       // start the calendar presentation
        $content = "";
        // a month is really a collection of weeks,
        // so we walk through the weeks of the month
        foreach ($this->Date->weeksOfMonth($month) as $week)
        {
            // print out every week until we're done with the month
            $content .= $this->week($week, $month, $dayContents);
        }
        // return as either a table or whatever's specified
        if ($this->presentAs === 'table')
        {
            if ($this->monthFormat)
            {
                // only show the month format if it is defined
                $string = $this->Date->date($month, $this->monthFormat);
                $link = $this->__makeLink($string, $month, 'month');
                $label = "<caption class='monthLabel'>$link</caption>";
            }
            else
            {
                $label = NULL;
            }
            return "$label\n<table class='$class'>$content</table>";
        }
        else
        {
            $pres = $this->presentAs;
            if ($this->monthFormat)
            {
                $string = $this->Date->date($month, $this->monthFormat);
                $link = $this->__makeLink($string, $month, 'month');
                $label = "<$pres class='monthLabel'>$link</$pres>";
            }
            else
            {
                $label = NULL;
            }
            return "<$pres class='$class'>$label\n$content</$pres>";
        }
    }

    /**
     * Return a week as a collection of days
     *
     * @param string $week - the week to display
     * @param string $month - a month that we may be displaying
     * @param string $dayContents - method of day output
     * @return a string containing an html week calendar
     *
     **/
    function week($week = NULL, $month = NULL, $dayContents = NULL)
    {
        // if no week is specified, use the selected value
        if (is_null($week))
        {
            $week = $this->dateValue;
        }
        // make sure it's a week
        $week = $this->Date->week($week);
        $class = 'week';

        if (isset($this->__highlights[$week]))
        {
            $class .= " ".implode(" ", $this->__highlights[$week]);
        }
        // choose how to print the day
        // set some param defaults
        $param_1 = $month;
        $param_2 = NULL;
        // choose the funcdtion to call and overide the params if needed
        switch (strtolower(substr($dayContents, 0, 5)))
        {
            case "agend":
                // close enough, assume "agenda"
                $dayContents = "agenda";
                break;
            case "sched":
                // this way we can accept sched:8-20
                if (strstr($dayContents, ":"))
                {
                    list(,$params) = split(":", $dayContents, 2);
                    if (strstr($params, "-"))
                    {
                        list($param_1, $param_2) = split("-", $params);
                    }
                }
                $dayContents = "schedule";
                break;
            default:
                $dayContents = "day";
                break;
        }
        // Now print all days in the week
        $content = "";
        foreach ($this->Date->daysOfWeek($week) as $day)
        {
            $content .= $this->$dayContents($day, $param_1, $param_2);
        }

        // return as either a table row or whatever's specified
        if ($this->presentAs === 'table')
        {
            // only show the week link if it's wanted
            if ($this->weekFormat)
            {
                $string = $this->Date->week($week, $this->weekFormat);
                $link = $this->__makeLink($string, $week, 'week');
                $label = "<td class='weekLabel'>$link</td>";
            }
            else
            {
                $label = NULL;
            }
            return "<tr class='$class'>$label\n$content</tr>";
        }
        else
        {
            $pres = $this->presentAs;
            // only show the week link if it's wanted
            if ($this->weekFormat)
            {
                $string = $this->Date->week($week, $this->weekFormat);
                $link = $this->__makeLink($string, $week, 'week');
                $label = "<$pres class='weekLabel'>$link</$pres>";
            }
            else
            {
                $label = NULL;
            }
            return "<$pres class='$class'>$label\n$content</$pres>";
        }
    }

    /**
     * Return a simple formatted day
     *
     * @param string $day - the day to display
     * @param string $month - a month that we may be displaying
     * @return a string containing an html calendar day
     *
     **/
    function day($day = NULL, $month = NULL)
    {
        $day = $this->__dayCheck($day);
        $class = 'day';

        // if month is passed that this day is not part of, set the
        // class to 'Blank' so that it can get different styling
        // otherwise give the day style based on day of week
        if (($month) AND ($this->Date->date($day, 'Y-m') != $month))
        {
            $class .= " Blank";
        }
        else
        {
            $class .= " ".$this->Date->date($day, 'D');
        }

        // if it's got highlighting, do that now
        if (isset($this->__highlights[$day]))
        {
            $class .= " ".implode(" ", $this->__highlights[$day]);
        }

        // get the standard day presentation
        $pday = $this->__dayHeader($day);

        // return as either a table cell or whatever's specified
        if ($this->presentAs === 'table')
        {
            return "<td class='$class'>$pday</td>\n";
        }
        else
        {
            $pres = $this->presentAs;
            return "<$pres class='$class'>$pday</$pres>\n";
        }
    }

    /**
     * Return a day with list of events
     *
     * @param string $day - the day to display
     * @return a string containing an html calendar day content
     *
     **/
    function agenda($day = NULL, $month = NULL)
    {
        $day = $this->__dayCheck($day);
        $class = 'agenda';

        // if month is passed that this day is not part of, set the
        // class to 'Blank' so that it can get different styling
        // otherwise give the day style based on day of week
        if (($month) AND ($this->Date->date($day, 'Y-m') != $month))
        {
            $class .= " Blank";
        }
        else
        {
            $class .= " ".$this->Date->date($day, 'D');
        }

        // if it's got highlighting, do that now
        if (isset($this->__highlights[$day]))
        {
            $class .= " ".implode(" ", $this->__highlights[$day]);
        }

        // get the standard day presentation
        $pday = $this->__dayHeader($day);
        // start our list
        $pday .= "<ul>";
        if (isset($this->__events[$day]))
        {
            foreach ($this->__events[$day] as $hour=>$time)
            {
                $pday .= $this->hour($hour);
            }
        }
        $pday .= "</ul>";

        // return as either a table cell or whatever's specified
        if ($this->presentAs === 'table')
        {
            return "<td class='$class'>$pday</td>\n";
        }
        else
        {
            $pres = $this->presentAs;
            return "<$pres class='$class'>$pday</$pres>\n";
        }
    }

    /**
     * Return a day with events in schedule format
     *
     * @param string $day - the day to display
     * @param string $begin - hour to begin schedule
     * @param string $end - hour to end schedule
     * @return a string containing an html calendar day content
     *
     **/
    function schedule($day = NULL, $begin = NULL, $end = NULL)
    {

        $day = $this->__dayCheck($day);
        $class = 'schedule';
        // give the schedule style based on day of week
        $class .= " ".$this->Date->date($day, 'D');

        // get the current time for highlighting
        $now = date('Y-m-d H');

        // get the standard day presentation
        $pday = $this->__dayHeader($day);
        // we need to know tomorrow to be sure not to fall into it
        $tomorrow = $this->Date->adjustDate($day, "+1 day");
        // make sure we have a start and stop point for our schedule
        // and that they're in the proper format
        if (is_null($begin))
        {
            $begin = $this->Date->datetime("$day 08", "Y-m-d H");
        }
        else
        {
            // let's force at least a legitamate range, just in case
            if (intval($begin) >= intval($end))
            {
                $begin = 0;
            }
            $begin = sprintf("%02u", $begin);
            $begin = $this->Date->datetime("$day $begin", "Y-m-d H");
        }
        if (is_null($end))
        {
            $end = $this->Date->datetime("$day 20", "Y-m-d H");
        }
        else
        {
            // make sure we don't go past midnight
            if ($end > 23)
            {
                $end = $this->Date->datetime("$tomorrow 00", "Y-m-d H");
            }
            else
            {
                $end = sprintf("%02u", $end);
                $end = $this->Date->datetime("$day $end", "Y-m-d H");
            }
        }
        // start our list
        $t = "$day 00";
        $pday .= "<ol>\n";
        // show any allday events now
        $pday .= $this->hour($day);
        // show hours pre-schedule only if they contain events
        // this is only run if our schedule does not start with hour 00
        if ($t !== "$begin") do
        {
            if(isset($this->__events[$day][$t]))
            {
                $tclass = $this->Date->datetime($t, "a");
                // mark this as outside of our schedule
                $tclass .= " nonschedule";
                $tvalue = $this->Date->datetime($t, $this->hourFormat);
                $pday .= "<li value='$tvalue' class='$tclass'>";
                $pday .= "<ul>";
                $pday .= $this->hour($t);
                $pday .= "</ul></li>\n";
            }
            $t = $this->Date->adjustDate($t, "+1 hour", "Y-m-d H");
        }
        while ($t !== "$begin");
        // show all hours in schedule, empty or not
        do
        {
            $tclass = $this->Date->datetime($t, "a");
            // mark this if it's the current hour
            if ($t == $now)
            {
                $tclass .= " now";
            }
            $tvalue = $this->Date->datetime($t, $this->hourFormat);
            $pday .= "<li value='$tvalue' class='$tclass'>";
            $pday .= "<ul>";
            $pday .= $this->hour($t);
            $pday .= "</ul></li>\n";
            $t = $this->Date->adjustDate($t, "+1 hour", "Y-m-d H");
        }
        while ($t !== "$end");
        // show hours post-schedule only if they contain events
        if ($t !== "$tomorrow 00") do
        {
            if(isset($this->__events[$day][$t]))
            {
                $tclass = $this->Date->datetime($t, "a");
                // mark this as outside of our schedule
                $tclass .= " nonschedule";
                $tvalue = $this->Date->datetime($t, $this->hourFormat);
                $pday .= "<li value='$tvalue' class='$tclass'>";
                $pday .= "<ul>";
                $pday .= $this->hour($t);
                $pday .= "</ul></li>\n";
            }
            $t = $this->Date->adjustDate($t, "+1 hour", "Y-m-d H");
        }
        while ($t !== "$tomorrow 00");

        $pday .= "</ol>\n";

        // return as either a table cell or whatever's specified
        if ($this->presentAs === 'table')
        {
            return "<td class='$class'>$pday</td>\n";
        }
        else
        {
            $pres = $this->presentAs;
            return "<$pres class='$class'>$pday</$pres>\n";
        }
    }

    /**
     * Return a list of events for a specific hour
     *
     * @param string $hour - the hour to display,
     *                        or 'none' for allday events
     * @param string $url - an optional url for event links
     * @return a string containing an html calendar day content
     *
     **/
    function hour($hour = NULL, $url = NULL)
    {
        $day = $this->Date->date($hour);
        if ((!$url) AND ($this->eventUrl))
        {
            $url = $this->eventUrl;
        }
        if (isset($this->__events[$day][$hour]))
        {
            $phour = "";
            if ($hour == $day)
            {
                // if hour sent = the day, then show the allday events
                foreach ($this->__events[$day][$day] as $event)
                {
                    $t = $event['title'];
                    $id = $event['id'];
                    // only show a link if a link was set
                    if ($url)
                    {
                        $lnk = $this->__makeLink($t, $id, NULL, $url);
                    }
                    else
                    {
                        $lnk = $t;
                    }
                    $class = "allday";
                    // only set the style if it exists
                    if ($event['style'])
                    {
                        $class .= " ".$event['style'];
                    }
                    $phour .= "<li class='$class'>$lnk</li>";
                }
            }
            else
            {
                // otherwise walk the hour's events and display them
                foreach ($this->__events[$day][$hour] as $ts=>$eventlist)
                {
                    $ts = $this->Date->datetime($ts, $this->timeFormat);
                    foreach ($eventlist as $event)
                    {
                        $t = $event['title'];
                        $id = $event['id'];
                        // only show a link if a link was set
                        if ($url)
                        {
                            $lnk = $this->__makeLink($t, $id, NULL, $url);
                        }
                        else
                        {
                            $lnk = $t;
                        }
                        // only show an endtime if we have one
                        if ($event['end'])
                        {
                            $end = $this->Date->datetime($event['end'],
                                                       $this->timeFormat);
                            $ts .= " - $end";
                        }
                        $class = "event";
                        // only set the style if it exists
                        if ($event['style'])
                        {
                            $class .= " ".$event['style'];
                        }
                        $phour .= "<li class='$class'>$ts: $lnk</li>";
                    }
                }
            }
        }
        else
        {
            $phour = "&nbsp;";
        }
        return $phour;
    }

    function __dayCheck($day)
    {
        // if no datetime is specified, use the selected value
        if (is_null($day))
        {
            $day = $this->dateValue;
        }
        // make sure it's in a proper format, and not a week
        if($this->Date->isWeek($day))
        {
            // we at least want a day, so let's select sunday
            $day = $this->Date->weekdayOfWeek(0, $day);
        }
        else
        {
            $day = $this->Date->date($day);
        }
        return $day;
    }


    /**
     * Return a day header
     *
     * @param string $day - the day to display
     * @return a string containing an html calendar day header
     *
     **/
    function __dayHeader($day)
    {
        // convert to the format
        $pday = $this->Date->date($day, $this->dayFormat);
        if ($this->dateType == 'week')
        {
            // if selected is a whole week, always set a link
            return $this->__makeLink($pday, $day);
        }
        elseif ($day != $this->dateValue)
        {
            // otherwise if it's not the current date, make it a link
            return $this->__makeLink($pday, $day);
        }
        else
        {
            // if all else fails, just return without a link
            return $pday;
        }
    }

    /**
     * Create a properly formatted link
     *
     * @param string $label - the label for the link
     * @param string $paramVal - the value of the named param
     * @param string $paramType - the name of the param
     * @return a string containing the formatted link
     *
     **/
    function __makeLink( $label, $paramVal,
                           $paramType = 'date', $url = NULL)
    {
        // only set the $param if it isn't the default
        if ($paramVal !== $this->default)
        {
            // only set a param label if it's wanted
            if (!is_null($paramType))
            {
                $param = sprintf($this->pf, $paramType, $paramVal);
            }
            else
            {
                $param = "/$paramVal";
            }
        }
        else
        {
            $param = NULL;
        }

        // make sure we have a url
        if (is_null($url))
        {
            $url = $this->url;
        }
        // if url is set, append a slash
        if (!is_null($url))
        {
            $url = $this->url."/";
        }

        // create the actual link
        $destination = $this->Html->url($url.$param);

        // create an AJAX style link
        if ($this->ajaxTarget)
        {
            return $this->Ajax->link( $label,
                                      $destination,
                                      array('update'=>$this->ajaxTarget));
        }
        // create an HTML style link
        else
        {
            return $this->Html->link($label, $destination);
        }
    }

// ######################################################################
// Helpful links - can be made Magic? or shared with paginate?
// ######################################################################

    /**
     * Create a link to previous year
     *
     * @param string $format - the label format for the link
     * @param string $datetime - the date to base it on
     * @return a string containing the formatted link
     *
     **/
    function prevYear($format = NULL, $datetime = NULL)
    {
        if(is_null($format))
        {
            $format = "< 'y";
        }
        // if date not specified, use $this->dateValue
        if(is_null($datetime))
        {
            $datetime = $this->dateValue;
        }
        // make sure it's in the proper format
        $year = $this->Date->adjustDate($datetime, "-1 year", 'Y');
        $label = $this->Date->date($year, $format);
        return $this->__makeLink($label, $year, 'year');
    }

    /**
     * Create a link to a specific year
     *
     * @param string $format - the label format for the link
     * @param string $datetime - the date to base it on
     * @return a string containing the formatted link
     *
     **/
    function thisYear($format = NULL, $datetime = NULL)
    {
        if(is_null($format))
        {
            $format = "Y";
        }
        // if date not specified, use $this->dateValue
        if(is_null($datetime))
        {
            $datetime = $this->dateValue;
        }
        // make sure it's in the proper format
        $year = $this->Date->date($datetime, 'Y');
        $label = $this->Date->date($datetime, $format);
        return $this->__makeLink($label, $year, 'year');
    }

    /**
     * Create a link to next year
     *
     * @param string $format - the label format for the link
     * @param string $datetime - the date to base it on
     * @return a string containing the formatted link
     *
     **/
    function nextYear($format = NULL, $datetime = NULL)
    {
        if(is_null($format))
        {
            $format = "'y >";
        }
        // if date not specified, use $this->dateValue
        if(is_null($datetime))
        {
            $datetime = $this->dateValue;
        }
        // make sure it's in the proper format
        $year = $this->Date->adjustDate($datetime, "+1 year", 'Y');
        $label = $this->Date->date($year, $format);
        return $this->__makeLink($label, $year, 'year');
    }

    /**
     * Create a link to previous month
     *
     * @param string $format - the label format for the link
     * @param string $datetime - the date to base it on
     * @return a string containing the formatted link
     *
     **/
    function prevMonth($format = NULL, $datetime = NULL)
    {
        if(is_null($format))
        {
            $format = "< M";
        }
        // if date not specified, use $this->dateValue
        if(is_null($datetime))
        {
            $datetime = $this->dateValue;
        }
        $month = $this->Date->adjustDate($datetime, "-1 month", 'Y-m');
        $label = $this->Date->date($month, $format);
        return $this->__makeLink($label, $month, 'month');
    }

    /**
     * Create a link to a specific month
     *
     * @param string $format - the label format for the link
     * @param string $datetime - the date to base it on
     * @return a string containing the formatted link
     *
     **/
    function thisMonth($format = NULL, $datetime = NULL)
    {
        if(is_null($format))
        {
            $format = "F Y";
        }
        // if date not specified, use $this->dateValue
        if(is_null($datetime))
        {
            $datetime = $this->dateValue;
        }
        // make sure it's in the proper format
        $month = $this->Date->date($datetime, 'Y-m');
        $label = $this->Date->date($month, $format);
        return $this->__makeLink($label, $month, 'month');
    }

    /**
     * Create a link to next month
     *
     * @param string $format - the label format for the link
     * @param string $datetime - the date to base it on
     * @return a string containing the formatted link
     *
     **/
    function nextMonth($format = NULL, $datetime = NULL)
    {
        if(is_null($format))
        {
            $format = "M >";
        }
        // if date not specified, use $this->dateValue
        if(is_null($datetime))
        {
            $datetime = $this->dateValue;
        }
        // make sure it's in the proper format
        $month = $this->Date->adjustDate($datetime, "+1 month", 'Y-m');
        $label = $this->Date->date($month, $format);
        return $this->__makeLink($label, $month, 'month');
    }

    /**
     * Create a link to previous week
     *
     * @param string $format - the sprintf format for the link
     * @param string $week - the yeardate to base it on
     * @return a string containing the formatted link
     *
     **/
    function prevWeek($format = NULL, $week = NULL)
    {
        if(is_null($format))
        {
            $format = '< wk %2$u';
        }
        // if date not specified, use $this->dateValue
        if(is_null($week))
        {
            $week = $this->dateValue;
        }
        // make sure it's in week format
        $week = $this->Date->week($week);
        $week = $this->Date->adjustWeek($week, -1);
        $label = $this->Date->week($week, $format);
        return $this->__makeLink($label, $week, 'week');
    }

    /**
     * Create a link to a specific week
     *
     * @param string $format - the sprintf format for the link
     * @param string $week - the yeardate to base it on
     * @return a string containing the formatted link
     *
     **/
    function thisWeek($format = NULL, $week = NULL)
    {
        if(is_null($format))
        {
            $format = 'week %2$u of %1$u';
        }
        // if date not specified, use $this->dateValue
        if(is_null($week))
        {
            $week = $this->dateValue;
        }
        // make sure it's in week format
        $week = $this->Date->week($week);
        $label = $this->Date->week($week, $format);
        return $this->__makeLink($label, $week, 'week');
    }

    /**
     * Create a link to next week
     *
     * @param string $format - the sprintf format for the link
     * @param string $week - the yeardate to base it on
     * @return a string containing the formatted link
     *
     **/
    function nextWeek($format = NULL, $week = NULL)
    {
        if(is_null($format))
        {
            $format = 'wk %2$u >';
        }
        // if date not specified, use $this->dateValue
        if(is_null($week))
        {
            $week = $this->dateValue;
        }
        // make sure it's in week format
        $week = $this->Date->week($week);
        $week = $this->Date->adjustWeek($week, +1);
        $label = $this->Date->week($week, $format);
        return $this->__makeLink($label, $week, 'week');
    }

    /**
     * Create a link to previous date
     *
     * @param string $format - the label format for the link
     * @param string $datetime - the date to base it on
     * @return a string containing the formatted link
     *
     **/
    function prevDate($format = NULL, $datetime = NULL)
    {
        if(is_null($format))
        {
            $format = "< jS";
        }
        // if date not specified, use $this->dateValue
        if(is_null($datetime))
        {
            $datetime = $this->dateValue;
        }
        // make sure it's in the proper format
        $datetime = $this->Date->adjustDate($datetime, "-1 day");
        $label = $this->Date->date($datetime, $format);
        return $this->__makeLink($label, $datetime, 'date');
    }

    /**
     * Create a link to a specific date
     *
     * @param string $format - the label format for the link
     * @param string $datetime - the date to base it on
     * @return a string containing the formatted link
     *
     **/
    function thisDate($format = NULL, $datetime = NULL)
    {
        if(is_null($format))
        {
            $format = "F j, Y";
        }
        // if date not specified, use $this->dateValue
        if(is_null($datetime))
        {
            $datetime = $this->dateValue;
        }
        // make sure it's in the proper format
        $datetime = $this->Date->date($datetime);
        $label = $this->Date->date($datetime, $format);
        return $this->__makeLink($label, $datetime, 'date');
    }

    /**
     * Create a link to next date
     *
     * @param string $format - the label format for the link
     * @param string $datetime - the date to base it on
     * @return a string containing the formatted link
     *
     **/
    function nextDate($format = NULL, $datetime = NULL)
    {
        if(is_null($format))
        {
            $format = "jS >";
        }
        // if date not specified, use $this->dateValue
        if(is_null($datetime))
        {
            $datetime = $this->dateValue;
        }
        // make sure it's in the proper format
        $datetime = $this->Date->adjustDate($datetime, "+1 day");
        $label = $this->Date->date($datetime, $format);
        return $this->__makeLink($label, $datetime, 'date');
    }

    /**
     * Create a link to previous, based on the current date type
     *
     * @param string $format - the label format for the link
     * @param string $datetime - the date to base it on
     * @return a string containing the formatted link
     *
     **/
    function prevLink($format = NULL, $datetime = NULL) {
        switch($this->dateType)
        {
            case 'year':
            case 'month':
            case 'week':
            case 'date':
                $cmd = "prev".Inflector::camelize($this->dateType);
        }
        return $this->$cmd($format, $datetime);
    }

    /**
     * Create a link to a specific, based on the current date type
     *
     * @param string $format - the label format for the link
     * @param string $datetime - the date to base it on
     * @return a string containing the formatted link
     *
     **/
    function thisLink($format = NULL, $datetime = NULL) {
        switch($this->dateType)
        {
            case 'year':
            case 'month':
            case 'week':
            case 'date':
                $cmd = "this".Inflector::camelize($this->dateType);
        }
        return $this->$cmd($format, $datetime);
    }

    /**
     * Create a link to next, based on the current date type
     *
     * @param string $format - the label format for the link
     * @param string $datetime - the date to base it on
     * @return a string containing the formatted link
     *
     **/
    function nextLink($format = NULL, $datetime = NULL) {
        switch($this->dateType)
        {
            case 'year':
            case 'month':
            case 'week':
            case 'date':
                $cmd = "next".Inflector::camelize($this->dateType);
        }
        return $this->$cmd($format, $datetime);
    }
}

?>