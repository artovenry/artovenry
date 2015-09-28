<?
namespace Art\ActiveRecord;

trait Persistence{
  function update($attributes){
    foreach($attributes as $name=>$value){
      $this->set($name, $value);
      $this->save();
    }
  }
  static function find($id){
    return static::find_one($id);
  }
  
  static function delete_all(){
    return static::delete_many();
  }
}

