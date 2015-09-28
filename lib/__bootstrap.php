<?
define ("VGT_PATH", __DIR__);
define ("VGT_PLUGIN_PATH", dirname(__DIR__));
define ("VGT_APP_PATH", dirname(__DIR__) . "/app");

require VGT_PATH . "/vendor/autoload.php";
require VGT_PATH . "/active_support.php";
require VGT_PATH . "/configuration.php";
require VGT_PATH . "/active_record.php";

date_default_timezone_set(Vgt\Configuration::config("global.timezone"));
Vgt\ActiveRecord\Connection::establish_connection();

spl_autoload_register(function($class){
  $str = preg_replace('/([A-Z])/', '_$1', $class);
  $str = strtolower($str);
  $str = str_replace('\\_', '/', $str);
  $str = ltrim($str, '_');
  $filepath= VGT_APP_PATH . "/models/{$str}.php";
  if(is_readable($filepath)) require $filepath;
});

