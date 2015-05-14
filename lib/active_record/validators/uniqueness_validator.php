<?
namespace Art\ActiveRecord\Validations;

class UniquenessValidator extends AbstractValidator{
	const TAKEN= "taken";

	static $default_options= ["scope"=>null	];

	function validate($record){
		$this->class_name= $class_name= get_class($record);

		foreach($this->validator_option as $key=>$value){
			$attr_name= is_int($key)? $value: $key;
			//if($record->is_belongs_to_association($attr_name))
			//	$attr_name= $record->belongs_to_foreign_key($attr_name);

			$options= is_array($value)? array_merge(self::$default_options, $value): self::$default_options;

			if(isset($options["scope"])){
				$scope= $options["scope"];
				//if($record->is_belongs_to_association($scope))
				//	$scope= $record->belongs_to_foreign_key($scope);
				$is_taken= $class_name::where($scope, $record->$scope)
					->where($attr_name, $record->$attr_name)->is_present();
			}else{
				$is_taken= $class_name::where($attr_name, $record->$attr_name)->is_present();
			}

			if(!$is_taken)continue;
			$record->errors()->add($attr_name, self::TAKEN);
		}
	}


}
