<?
require __DIR__ . "/active_record/errors.php";
require __DIR__ . "/active_record/validations.php";
require __DIR__ . "/active_record/connection.php";
require __DIR__ . "/active_record/orm_wrapper.php";
require __DIR__ . "/active_record/base.php";



Art\Configuration::setup([
  //"migrations_path"=>ART_APP_PATH . "/" . Art\ActiveRecord\Migration::PATH
], "active_record");

/**
[BUG]
@fixme
*/
$database_opts= [
  "password"=> defined("DB_PASSWORD")? DB_PASSWORD: "",
  "hostname"=> defined("DB_HOST")? DB_HOST: "localhost",
  "logging"=> true
];

foreach($database_opts as $key=>$value)
  Art\Configuration::setup(array($key=>$value), "active_record.database");
