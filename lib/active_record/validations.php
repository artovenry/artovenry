<?
namespace Art\ActiveRecord\Validations;
const VALIDATORS="presence uniqueness format length numericality";

abstract class AbstractValidator{
	protected $validator_option;

	abstract function validate($record);

	static function run($record){
		$validator= new static($record);
		$validator->validate($record);
	}

	protected function __construct($record){
		$class_name= get_class($record);
		$validator_class_name= str_replace(__NAMESPACE__ . "\\", "", get_called_class());
		$_validator= sprintf('_validates_%s_of',
			\Art\to_lowercase(str_replace("Validator", "", $validator_class_name))
	  );
	  if(method_exists($class_name, $_validator))
			$this->validator_option= $class_name::$_validator();
		else
			$this->validator_option= $class_name::$$_validator;
	}
}

require __DIR__ ."/validators/uniqueness_validator.php";
require __DIR__ ."/validators/presence_validator.php";
require __DIR__ ."/validators/format_validator.php";
require __DIR__ ."/validators/length_validator.php";
require __DIR__ ."/validators/numericality_validator.php";
