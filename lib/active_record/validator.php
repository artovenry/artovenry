<?
namespace Art\ActiveRecord;
use Art\ActiveModel\Errors;
use Art\ActiveRecord\Validations;

trait Validator{

	private $errors;

	function errors(){
		if(!isset($this->errors))$this->errors= new Errors($this);
		return $this->errors;
	}

  function save(){
  	return $this->save_with_raise(false);
  }

	function save_with_raise($raise= true){
  	if(FALSE === $this->validate_with_raise($raise))
  		return false;
  	if($rs= parent::save())return $rs;
  	if($raise)throw new RecordNotSaved;
  	return false;
	}


  function validate(){
  	return $this->validate_with_raise(false);
  }


	function validate_with_raise($raise= true){
  	foreach(self::validators() as $validator)
  		$validator::run($this);
  	foreach($this->custom_validators() as $method_name)
	  	$this->$method_name();
  	if($this->is_valid())return true;
  	if($raise)throw new RecordInvalid($this);
  	return false;
	}


	function is_valid(){
		return !$this->is_invalid();
	}
	function is_invalid(){
		return $this->errors()->exists();
	}

	private function custom_validators(){
		$class_name= get_called_class();
		$macro_name= "_validate";
		if(!isset($class_name::$$macro_name))return [];
		return $class_name::$$macro_name;
	}

	private static function validators(){
		$validator_names= split(" ", Validations\VALIDATORS);
		$class_name= get_called_class();
		return array_reduce($validator_names, function($rs, $item) use($class_name){
			$macro_name= "_validates_${item}_of";
			if(!isset($class_name::$$macro_name) && !method_exists($class_name, $macro_name))return $rs;

		  $class_name= __NAMESPACE__ . "\\Validations\\" . \Art\to_uppercase($item) . "Validator";
			array_push($rs, $class_name);
			return $rs;
		},[]);

	}



}