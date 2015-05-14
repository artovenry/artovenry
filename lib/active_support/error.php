<?
namespace Art;

class Error extends \ErrorException{
  function __construct($message=null){
    $this->message= $message;
    parent::__construct();
  }

  function to_json(){
    return json_encode(["message"=>$this->message]);
  }
}