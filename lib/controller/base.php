<?
namespace Art\Controller;

class StatusCodes{
  static $ok= 200;
  static $created= 201;
  static $bad_request= 400;
  static $server_error= 500;
}


abstract class Base{
  protected $server;

  function __construct($server) {
    $this->server = $server;
  }

  static function initialize(){
    static::add_endpoints();
  }
  
  protected function success($json, $code= "ok"){
    header('Content-Type: application/json; charset=UTF-8', true, StatusCodes::$$code);
    echo $json;
    exit;
    //return new \WP_JSON_Response($data, StatusCodes::$$code);
  }

  protected function error($e, $code){
    header('Content-Type: application/json; charset=UTF-8', true, StatusCodes::$$code);
    echo $e->to_json();
    exit;
    //return new \WP_Error("", $e->getMessage(),["status"=>StatusCode::$$code]);
  }


  protected function created($data=""){
    return $this->success($data, "created");
  }

  protected function read($data=""){
    return $this->success($data, "ok");
  }

  protected function bad_request($e){
    return $this->error($e, "bad_request");
  }

  protected function server_error($e){
    return $this->error($e, "server_error");
  }


  private static function add_endpoints(){
    $class= get_called_class();
    add_action("wp_json_server_before_serve", function($server) use($class){
      add_filter("json_endpoints", function($routes) use($class, $server){
        $instance= new $class($server);
        $new_routes= $class::routes();
        foreach($new_routes as &$route){
          $route= array_map(function($item) use ($instance){
            return [[$instance, $item[0]], $item[1]];
          }, $route);
        }
        return array_merge($routes, $new_routes);
      });
    });
  }  
}

