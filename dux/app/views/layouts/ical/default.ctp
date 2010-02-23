<?php header('Content-type: text/calendar;charset=UTF-8')?>
<?php if(!empty($mod_date_for_layout)) header('Last-Modified: '.$time->toRSS($mod_date_for_layout))?>
BEGIN:VCALENDAR
VERSION:2.0
CALSCALE:GREGORIAN
METHOD:PUBLISH
<?php if(!empty($mod_date_for_layout)):?>
LAST-MODIFIED:<?php echo $ical->dateToUTC($ical->dateToUTC($mod_date_for_layout))?>
<?php endif;?>
X-WR-CALNAME:<?php echo $title_for_layout?>
PRODID:-//Web1984//Dux//EN
<?php echo $content_for_layout;?>
END:VCALENDAR