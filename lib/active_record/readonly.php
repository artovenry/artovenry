<?
namespace Art\ActiveRecord;

class RecordOnlyRecord extends ActiveRecordError{}

trait Readonly{
	function save_with_raise(){
    throw new RecordOnlyRecord($this);
	}

	function destroy_with_raise(){
    throw new RecordOnlyRecord($this);
	}
}