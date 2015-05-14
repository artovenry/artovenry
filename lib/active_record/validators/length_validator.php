<?
namespace Art\ActiveRecord\Validations;

class LengthValidator extends AbstractValidator{
	const WRONG_LENGTH= "wrong_length";

	/**
	@todo
		_validate_numericality_of=>[
			"hoge"=> "3..4"
			"boo"=>"5...32"
			"baa"=>5
			"boo"=>"..5"
			"boo"=>"4..."

	*/

	function validate($record){
		foreach($this->validator_option as $attr_name=>$range){
			if(is_numeric($range)){
				$range= "{$range}..{$range}";
			}elseif (is_array($range)) {
				$range= "{$range[0]}..{$range[1]}";
			}

			if($this->in($range, $record->$attr_name))continue;
			$record->errors()->add($attr_name, self::WRONG_LENGTH);
		}
	}

	private function in($range, $value){
		$length= mb_strlen($value);
		if(!preg_match("/\A(\d*)(\.\.)(\.)?(\d*)\z/", $range, $matches))return false;
		$from= !empty($matches[1])? $matches[1]: 0;
		$to= $matches[4];
		$exclude_end= !empty($matches[3]);

		if($exclude_end){
			if(!empty($to)){
				return $from < $length && $length < $to;
			}else{
				return $from < $length;
			}
		}else{
			if(!empty($to)){
				return $from <= $length && $length <= $to;
			}else{
				return $from <= $length;
			}
		}
	}
}
