<?
if(!defined("ART_APP_PATH"))die;

class Artovenry{
  static function boot(){
    require "vendor/autoload.php";
    require "lib/active_support.php";
    require "lib/configuration.php";
    require "lib/active_model.php";
    require "lib/active_record.php";
    require "lib/controller.php";

    date_default_timezone_set(Art\Configuration::config("global.timezone"));
    Art\ActiveRecord\Connection::establish_connection();

    spl_autoload_register(function($class){
      $str= Art\to_lowercase($class);
      $filepath= ART_APP_PATH . "/models/{$str}.php";
      if(is_readable($filepath)) require $filepath;
    });

    spl_autoload_register(function($class){
      $str= Art\to_lowercase($class);
      $filepath= ART_APP_PATH . "/controllers/{$str}.php";
      if(is_readable($filepath)) require $filepath;
    });

    spl_autoload_register(function($class){
      $str= Art\to_lowercase($class);
      $filepath= ART_APP_PATH . "/lib/{$str}.php";
      if(is_readable($filepath)) require $filepath;
    });

    set_error_handler(function($no, $str, $file, $line){
      //throw new Art\Error($str, 0, $no, $file, $line);
      throw new Art\Error($str);
    },  E_ERROR);    
  }
  
  
}
