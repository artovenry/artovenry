<?
namespace Art\DateTime;

const MYSQL_DATETIME_FORMAT= 'Y-m-d H:i:s'; // 0000-00-00 00:00:00

trait Calculations{
  static function now(){
    return new static("now");
  }

  static function today(){
    return new static("today");
  }

  static function tomorrow(){
    return new static("tomorrow");
  }
}
