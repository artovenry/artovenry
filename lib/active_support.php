<?
namespace Art;
require __DIR__ . "/active_support/date_time.php";
require __DIR__ . "/active_support/error.php";

class Yaml extends \Symfony\Component\Yaml\Yaml{}


function to_lowercase($str){
  $str = preg_replace('/([A-Z])/', '_$1', $str);
  $str = strtolower($str);
  $str = str_replace('\\_', '/', $str);
  $str = ltrim($str, '_');
  return $str;  
}

function to_uppercase($str){
  // hoge_special_controller---> HogeSpecialController
  $words= explode("_", $str);
  foreach($words as &$word)$word= ucwords($word);
  return join("",$words);  
}

function like_hash($arg){
  if(!is_array($arg))return false;
  if(empty($arg))return true;

  foreach(array_keys($arg) as $key)
    if(!is_int($key)) return true;
  return false;
}

class DateTime extends \DateTime{
  use DateTime\Calculations;
  
  function to_s($format= DateTime\MYSQL_DATETIME_FORMAT){
    return $this->format($format);
  }
}


class Date extends \DateTime{
  use DateTime\Calculations;

  const FORMAT= 'Y-m-d'; // 0000-00-00
  
  function to_s(){
    return $this->format(self::FORMAT);
  }
}
