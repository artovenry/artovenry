<?
namespace Art\ActiveRecord\Validations;

class FormatValidator extends AbstractValidator{
	const INVALID= "invalid";
	const DATETIME_FORMAT= "/\A\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}\z/";
	const DATE_FORMAT= "/\A\d{4}\-\d{2}\-\d{2}\z/";
	//const INTEGER_FORMAT= "/\A0|([1-9]+\d*)\z/";

	function validate($record){
		foreach($this->validator_option as $attr_name=>$format){
			if(is_array($format)){
				$format= array_shift($format);
				$options= $format;
			}
			
			if($format === "datetime")$format= self::DATETIME_FORMAT;
			if($format === "date")$format= self::DATE_FORMAT;
			//if($format === "integer")$format= self::INTEGER_FORMAT;
			if(preg_match($format, $record->$attr_name))continue;
			$record->errors()->add($attr_name, self::INVALID);
		}
	}
}
