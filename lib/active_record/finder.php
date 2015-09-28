<?
namespace Art\ActiveRecord;

trait Finder{
  function take($limit=0){
    if(!$limit)return  $this->find_one();
    return $this->limit($limit)->find_many();
  }
  function all(){
    return $this->find_many();
  }

  function find_one($id=null) {
    $instance= $this->_create_model_instance(parent::find_one($id));
    if(empty($instance)) throw new RecordNotFound;
    return $instance;
  }

  function find_many() {
      $results = parent::find_many();
      foreach($results as $key => $result) {
          $results[$key] = $this->_create_model_instance($result);
      }
      return $results;
  }

}
