<?
require __DIR__ . "/controller/base.php";
require __DIR__ . "/controller/json_server.php";

add_action("init", function(){
  add_filter("wp_json_server_class", function(){
    return "Art\Controller\JsonServer";
  });
  foreach(glob(ART_APP_PATH . "/controllers/helpers/*.php") as $filename)
    require $filename;
  foreach(glob(ART_APP_PATH . "/controllers/*.php") as $filename){
    require $filename;
    $classname= Art\to_uppercase(basename($filename, ".php"));
    $classname::initialize();
  }
});

