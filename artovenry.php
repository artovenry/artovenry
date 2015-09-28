<?
if(!defined("ART_APP_PATH"))die;

class Artovenry{
  static function boot(){
    require "vendor/autoload.php";
    require "lib/active_support.php";
    require "lib/configuration.php";
    require "lib/active_record.php";
    require "lib/abstract_controller.php";

    date_default_timezone_set(Art\Configuration::config("global.timezone"));
    Art\ActiveRecord\Connection::establish_connection();

    spl_autoload_register(function($class){
      $str= Art\to_lowercase($class);
      $filepath= ART_APP_PATH . "/models/{$str}.php";
      if(is_readable($filepath)) require $filepath;
    });


    self::load_controllers();    
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
  }
  
  
  private static function load_controllers(){
    foreach(glob(ART_APP_PATH . "/controllers/helpers/*.php") as $filename)
      require $filename;
    foreach(glob(ART_APP_PATH . "/controllers/*.php") as $filename){
      require $filename;
      $classname= Art\to_uppercase(basename($filename, ".php"));
      $classname::initialize();
    }
  }
}
