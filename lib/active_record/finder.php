<?
namespace Art\ActiveRecord;

class Collection extends \ArrayObject{
  function to_json(){
    $rs= [];
    foreach($this as $record){
      $_record= $record->as_array();
      foreach($_record as $key=>&$value){
        $classname= get_class($this);
        if(!($value instanceof $classname))continue;
        $_hoge= [];
        foreach($value as $_value)
          $_hoge[]= $_value->as_array();
        $value= $_hoge;

      }

      $rs[]= $_record;
    }
    //var_dump($rs);exit;
    return json_encode($rs, SCRIPT_DEBUG? JSON_PRETTY_PRINT: null);
  }
}


trait Finder{
  protected $_prop_reset = [];

  function take($limit=0){
    if(!$limit)return  $this->find_one();
    return $this->limit($limit)->find_many();
  }
  function all(){
    return $this->find_many();
  }

  protected function props(){
    $props= [
      "_result_columns", 
      "_join_sources", 
      "_distinct", 
      "_raw_query", 
      "_raw_parameters", 
      "_where_conditions", 
      "_limit", 
      "_offset", 
      "_order_by", 
      "_group_by",
      "_having_conditions",
    ];
    return $props;
  }

  function reset($prop){
    foreach($this->props() as $p){
      if($p !== "_{$prop}")continue;
      $_p= "_{$prop}";
      $this->_prop_reset[]= $_p;
    }
    return $this;
  }

  function find($id){
    if(method_exists($class_name, "_default_scope")){
      $instance= $this->_create_model_instance(
        $class_name::filter("_default_scope")
        ->merge($this)
        ->__find_one($id)
      );
    }else{
      $instance= $this->_create_model_instance($this->__find_one($id));
    }
    if(empty($instance)) throw new RecordNotFound;
    return $instance;
  }

  function find_one($id=null) {
    $class_name= $this->_class_name;
    if(method_exists($class_name, "_default_scope")){
      $instance= $this->_create_model_instance(
        //$this->filter("_default_scope")->__find_one($id)
        $class_name::filter("_default_scope")
        ->merge($this)
        ->__find_one($id)
      );
    }else{
      $instance= $this->_create_model_instance($this->__find_one($id));
    }

    if(empty($instance)) return false;
    return $instance;
  }

  protected function __find_one($id=null){
    return parent::find_one($id);
  }
  protected function __find_many(){
    return new Collection(parent::find_many());
  }

  function merge($orm){
    foreach($this->props() as $prop){
      //overrides preceding props
      if(!empty($orm->$prop))$this->$prop= $orm->$prop;
      //reset props
      foreach($orm->_prop_reset as $reset){
        unset($this->$reset);

      }
    }
    return $this;
  }

  function find_many() {
    $class_name= $this->_class_name;
    if(method_exists($class_name, "_default_scope")){
      //$results= $this->filter("_default_scope")->__find_many();
      $results= $class_name::filter("_default_scope")
        ->merge($this)
        ->__find_many();
    }else{
      $results = $this->__find_many();
    }

    foreach($results as $key => $result) {
        $results[$key] = $this->_create_model_instance($result);
    }
    return $results;
  }

  function pluck($args){
    return $this->select_many($args);
  }


  function is_present($id=null){
    /**
    @fixme
    for better performance!
    */
    //$instance= $this->_create_model_instance(parent::find_one($id));
    //return !empty($instance);
    if(!empty($id)){
      return count($this->where_id_is($id)->select_expr('1', "one")->_run()) > 0;
    }else{
      return count($this->select_expr('1', "one")->_run()) > 0;
    }
  }

}
