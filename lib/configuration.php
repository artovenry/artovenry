<?
namespace Art;

class Configuration{
  const CONFIG_PATH= "config/config.yml";
  
  private static $config;

  static function set_config($option, $scope=null){
    $key= array_shift(array_keys($option));
    $value= array_shift(array_values($option));
    if(!is_string($value) AND !is_numeric($value) AND !is_bool($value))return false;
    $key_string= self::key_string($scope . "." . $key);

    //eval("if(empty(self::\$config$key_string)) return;");
    eval("self::\$config$key_string= \$value;");
  }

  static function get_config($scope){
    $key_string= self::key_string($scope);
    eval("\$data= self::\$config$key_string;");
    return $data;
  }

  
  // alias for get_config
  static function config($scope){
    return self::get_config($scope);
  }
  
  // alias for set_config
  static function setup($option, $scope=null){
    return self::set_config($option, $scope);
  }
  static function load(){
    self::$config= Yaml::parse(file_get_contents(ART_APP_PATH . "/" . self::CONFIG_PATH));
  }

  private static function key_string($scope){
    //hoge.boo.bar ----> ["hoge"]["boo"]["bar"]
    $rs="";
    foreach(explode(".", $scope) as $_name)
      $rs .= sprintf('["%s"]', $_name);
    return $rs;
  }
}

Configuration::load();
