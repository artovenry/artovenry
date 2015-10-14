<?
namespace Art;

abstract class AbstractController{
  static function initialize(){
    self::add_endpoints();
    //self::add_filters(); //[TODO}
  }

  //private static function add_filters(){}

  private static function add_endpoints(){
    $instance= new static;
    $classname= get_called_class();
    add_action("wp_json_server_before_serve", function() use($instance, $classname){
        add_filter("json_endpoints", function($routes) use($instance, $classname){
          //$instance= new static;

        $new_routes= $classname::routes();
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

