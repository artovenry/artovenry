<?
namespace Art;

abstract class AbstractController{
  static function initialize(){
    self::add_endpoints();
    //self::add_filters(); //[TODO}
  }
  
  //private static function add_filters(){}
  
  private static function add_endpoints(){
    add_action("wp_json_server_before_serve", function(){
      add_filter("json_endpoints", function($routes){
        $instance= new static;

        $new_routes= static::routes();
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

