<?
namespace Art\ActiveRecord;

trait Initializer{
  function build($attrs=null){
    return $this->create($attrs);
  }

}

