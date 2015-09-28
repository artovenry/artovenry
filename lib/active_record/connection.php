<?
namespace Art\ActiveRecord;

class Connection{
  const DB_CONFIG_PATH= "config/database.yml";
  private static $connection;

  static function retrieve(){
    if(self::connected())return self::$connection;
    self::establish_connection();
    return self::$connection;
  }
  
  static function establish_connection(){
    if(self::connected())return;
    \ORM::configure(self::configuration());
    self::$connection= \ORM::get_db();
  }
  
  private static function configuration(){
    $config= \Art\Configuration::config("active_record.database");
    $host= empty($config["hostname"])? "": "host={$config['hostname']}";
    $dbname= empty($config["database"])? "": "dbname={$config['database']}";
    $unix_socket= empty($config["socket"])? "": "unix_socket={$config['socket']}";
    return array(
      "connection_string"=>'mysql:' . join(";", [$host, $dbname, $unix_socket]),
      "username"=>$config["user"],
      "password"=>$config["password"],
      "logging"=>$config["logging"]
    );
  }
  
  
  private static function connected(){
    isset (self::$connection);
  }  
}