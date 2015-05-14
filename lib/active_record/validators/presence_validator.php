<?
namespace Art\ActiveRecord\Validations;

class PresenceValidator extends AbstractValidator{
	const BLANK= "blank";

	function validate($record){
		foreach($this->validator_option as $attr_name){
			if($this->is_present($record, $attr_name))continue;
			$record->errors()->add($attr_name, self::BLANK);
		}
	}

	private function is_present($record, $attr_name){
		$value= $record->$attr_name;
		if(!preg_match("/_id\z/", $attr_name)){
			return ($value == 0) || !empty($value);
		}else{
			if(!isset($value)){
				return false;
			}else{
				$class_name= \Art\to_uppercase(str_replace("_id", "", $attr_name));
				return $class_name::is_present($value);
			}
		}
	}
}
