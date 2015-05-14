<?
namespace Art\ActiveRecord\Validations;

class NumericalityValidator extends AbstractValidator{
	const INVALID= "not_a_number";
	const INT_REGEX= "/\A[+-]?\d+\z/";

	private static $default_options=[
		"only_integer"=>false,
		"allow_null"=>false
	];

	function validate($record){
		foreach($this->validator_option as $attr_name=>$options){
			$attr_name= is_int($attr_name)? $options: $attr_name;
			$options= is_array($options)? array_merge(self::$default_options, $options): self::$default_options;

			$value= $record->$attr_name;
			if($value === "")$value= null;
			if($options["allow_null"])
				if(is_null($value))continue;
			if($options["only_integer"])
				if(preg_match(self::INT_REGEX, $value))continue;
			if(is_numeric($value))continue;
			$record->errors()->add($attr_name, self::INVALID);
		}
	}
}
