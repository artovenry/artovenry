<?
namespace Art\ActiveRecord;

/**
@deprecated
@fixme
*/
class AssociationNotDefined extends ActiveRecordError{}

trait Association{
  protected function call_associations($name, $args){
    $assocs= "belongs_to has_one has_many has_many_through";
    foreach(explode(" ", $assocs) as $assoc){
      if(!$this->is_association_for($assoc, $name))continue;
      return $this->call_association($assoc, $name, $args);
    }
    throw new AssociationNotDefined;
  }

  protected function call_association($assoc, $name, $args){
    $_assoc= "_" . $assoc;
    $_static= static::$$_assoc;

    $class_name= $this->assoc_class_name($_static, $name);
    if(!\Art\like_hash($_static))
        return $this->$assoc($class_name)->take();

    $foreign_key= $this->assoc_foreign_key($_static, $name);

    if($assoc == "belongs_to"){
      $primary_key= $this->assoc_primary_key($_static, $name);
    }else{
      $primary_key= $this->current_primary_key($_static, $name);
    }

    $finder= ($assoc == "has_one" || $assoc == "belongs_to")? "take": "all";
    return $this->$assoc($class_name, $foreign_key, $primary_key)->$finder();
  }


  /**
  @todo
  複数形問題
  */
  function assoc_class_name($assoc, $name){
    if($class_name= $assoc[$name]["class_name"])
      return $class_name;
    return \Art\to_uppercase($name);
  }

  /**
  @todo
  複数形問題
  */
  function assoc_foreign_key($assoc, $name){
    if($foreign_key= $assoc[$name]["foreign_key"])
      return $foreign_key;
    if($this->is_belongs_to_association($name)) return $name . "_id";
    return ***_id;
  }

  function assoc_primary_key($assoc, $name){
    $class_name= $this->assoc_class_name($assoc, $name);
    if(isset($class_name::$_id_column))return $class_name::$_id_column;
    return "id";
  }

  function current_primary_key($name){
    $class_name= get_called_class();
    if(isset($class_name::$_id_column))return $class_name::$_id_column;
    return "id";
  }

  function is_association_for($assoc, $name){
    $_assoc= "_" . $assoc;
    if(!isset(static::$$_assoc))return false;
    $association= static::$$_assoc;
    $association_names= \Art\like_hash($association)? array_keys($association): array_values($association);
    if(array_search($name, $association_names) === FALSE)return false;

    return true;
  }


  function is_belongs_to_association($name){
    return $this->is_association_for("belongs_to", $name);
  }

/**
@todo 
*/
  function belongs_to_foreign_key($name){
    return $this->assoc_foreign_key(static::$_belongs_to, $name);
  }

/**
@todo 
*/
  function belongs_to_class_name($name){
    return $this->assoc_class_name(static::$_belongs_to, $name);
  }

/*
  function foreign_key_for($name){
    $assoc_class_name= $this->assoc_class_name($);
    $base_table_name = self::_get_table_name(get_class($this));
    return self::_build_foreign_key_name($name, $base_table_name);
  }
*/

}
