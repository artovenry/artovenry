<?
namespace Art\ActiveModel;

trait Schema{
  protected static $schema;
  
  protected static function load_schema(){
    $name= static::_get_table_name(get_called_class());
    $path= ART_APP_PATH . "/models/schemas/{$name}.yaml";
    return (object)\Art\Yaml::parse(file_get_contents($path));
  }
}

class SchemaValidator{
  
  
}
