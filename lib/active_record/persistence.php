<?
namespace Art\ActiveRecord;

trait Persistence{

  
  static function delete_all(){
    return static::delete_many();
  }

  static function create($attributes=[]){
    return static::create_with_raise($attributes, false);
  }

  static function create_with_raise($attributes=[], $raise=true){
    if(\Art\like_hash($attributes)){
      $record= parent::create($attributes);
      $record->save_with_raise($raise);
      return $record;
    }
 
    $records= [];
    foreach($attributes as $attrs)
      $records[]= static::create_with_raise($attrs, $raise);
    return $records;
  }


  function update($attributes){
    return $this->update_with_raise($attributes, false);
  }

  function update_with_raise($attributes, $raise=true){
    foreach($attributes as $name=>$value)
      $this->set($name, $value);
    return $this->save_with_raise($raise);
  }

}
