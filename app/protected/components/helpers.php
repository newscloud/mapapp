<?php
// protected/components/helpers.php

function getUserId() {
  if(Yii::app()->user === null)
    $this->redirect(Yii::app()->getModule('user')->loginUrl);  
  else
     return Yii::app()->user->id;
}

function getUserProfileThumb($id=0) {
  $str = Yii::app()->getModule('user')->user($id)->profile->profile_image_url;
  if ($str == '') 
    $str = 'http://cloud.geogram.com/images/default_profile.jpg';
  else if (stripos($str, 'http')===false)
    $str='/'.$str;
  return $str;
}
 
function getUserProfile($id=0) {
  return Yii::app()->getModule('user')->user($id)->profile;
}

function getFirstName() {
  if (Yii::app()->user->isGuest)
    return '';
  else
    return ellipsize(ucwords(getUserProfile(Yii::app()->user->id)->getAttribute('first_name')),15).(Yii::app()->user->isAdmin()?'*':'');
}

function displayDistance($distance) {
  $distance = floatval($distance);
  if ($distance == 0)
    return 'your location';
  else
    return ltrim(round($distance,2).' miles','0');
}

function getUserFullName($id=0) {
  $fullname = getUserProfile($id)->getAttribute('first_name').' '.substr(getUserProfile($id)->getAttribute('last_name'),0,1).'.';
  $fullname = ucwords($fullname);
  return $fullname;
}

// formatting helpers
function inbox_date($time_str) {
  $tstamp = strtotime($time_str);
  if (date('z',time()) <= date('z',$tstamp))
    $date_str = Yii::app()->dateFormatter->format('h:mm a',CDateTimeParser::parse($tstamp, 'yyyy-MM-dd'),'medium',null);
    else
    $date_str = Yii::app()->dateFormatter->format('MMM d',CDateTimeParser::parse($tstamp, 'yyyy-MM-dd'),'medium',null);  
  return $date_str;
}

function hyperlink($str) {
  return preg_replace( '@(?<![.*">])\b(?:(?:https?|ftp|file)://|[a-z]\.)[-A-Z0-9+&#/%=~_|$?!:,.]*[A-Z0-9+&#/%=~_|$]@i', '<a href="\0">\0</a>', $str );
}

/** This function will strip tags from a string, split it at its max_length and ellipsize
         * @param       mixed   int (1|0) or float, .5, .2, etc for position to split
         * @param       string  ellipsis ; Default '...'
         * @return      string  ellipsized string
*/
function ellipsize($str, $max_length, $position = 1, $ellipsis = '&hellip;')
{
        // Strip tags
        $str = trim(strip_tags($str));

        // Is the string long enough to ellipsize?
        if (strlen($str) <= $max_length)
        {
                return $str;
        }

        $beg = substr($str, 0, floor($max_length * $position));
        $position = ($position > 1) ? 1 : $position;

        if ($position === 1)
        {
                $end = substr($str, 0, -($max_length - strlen($beg)));
        }
        else
        {
                $end = substr($str, -($max_length - strlen($beg)));
        }

        return $beg.$ellipsis.$end;
}

/* Converts an integer into the alphabet base (a-z). @author Theriault via php docs  */
function num2alpha($n) {
    $r = '';
    for ($i = 1; $n >= 0 && $i < 10; $i++) {
        $r = chr(0x61 + ($n % pow(26, $i) / pow(26, $i - 1))) . $r;
        $n -= pow(26, $i);
    }
    return $r;
}

/* Converts an alphabetic string into an integer. @author Theriault  via php docs */
function alpha2num($a) {
    $r = 0;
    $l = strlen($a);
    for ($i = 0; $i < $l; $i++) {
        $r += pow(26, $i) * (ord($a[$l - $i - 1]) - 0x60);
    }
    return $r - 1;
}  

/* debugging and logging functions */
function varDumpToString ($var)
{
    ob_start();
    var_dump($var);
    $result = ob_get_clean();
    return $result;
}

function lg($msg='',$category='default'){
  Yii::log($msg, CLogger::LEVEL_INFO,$category);
}

function lb($n=1) {
    echo '<br />';
    echo '<br />';
}

function yexit() {
  Yii::app()->end();
}

?>