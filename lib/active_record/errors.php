<?
namespace Art\ActiveRecord;

abstract class ActiveRecordError extends \Art\Error{
	private $errors;
	private $record;

	function __construct($record=null){
		if(!empty($record)){
			$this->record= $record;
			$this->errors= $record->errors();
		}
		parent::__construct();
	}

	function errors(){
		return $this->errors;
	}
	function record(){
		return $this->record;
	}

	function to_json(){
		if($this->errors) $errors= $this->errors->to_a();
		if($this->record) $record= $this->record->to_a();
		return json_encode([
			"errors"=>$errors,
			"record"=>$record
		]);
	}
}

class RecordNotSaved extends ActiveRecordError{}           
class RecordNotFound extends ActiveRecordError{}
class RecordInvalid extends ActiveRecordError{}
class AttributeNotFound extends ActiveRecordError{}
