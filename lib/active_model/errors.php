<?
namespace Art\ActiveModel;

class Errors{
  const BASE= "base";

  protected $base;
  protected $messages;

  /*
  [
    "attr_name"=>["message..","message..","message..",,,],
    "attr_name"=>["message..","message..","message..",,,],
  ]
  */

  function __construct($base){
    $this->base= $base;
    $this->messages= [];
  }

/*
  function full_message($attribute, $message){
    if($attribute===self::BASE)return $message;
    $attr_name= \Art\humanize($attribute);
    $attr_name= get_class($this->base)::human_attribute_name(
      $attribute, ["default"=>$attr_name]
    );

    return \Art\t("errors.format",[
      "default"=> "%{attribute} %{message}",
      "attribute"=> $attr_name,
      "message"=> $message
    ]);
  }
*/

  function has_key($name){
    return isset($this->messages[$name]);
  }

  function to_a(){
    return $this->full_messages();
  }

  function add($name, $message){
    if(!isset($this->messages[$name]))
      $this->messages[$name]= [$message];
    else
      array_push($this->messages[$name], $message);
  }
  
  function messages(){    return $this->messages;  }
  function messages_for($name){    return $this->messages[$name];  }

  function full_messages(){
    $rs= [];
    foreach($this->messages as $name=>$messages)
      foreach($messages as $m) array_push($rs, $name . " ". $m);
    return $rs;
  }
  function full_messages_for($name){
    $rs= [];
    foreach($this->messages_for($name) as $message)
      array_push($rs, $name . " ". $message);
    return $rs;
  }
  function count(){
    return array_reduce($this->messages, function($count, $item){
      return $count= $count+ count($item);
    },0);
  }
  function exists(){ return $this->count() >0;  }

  /**
  @todo
  */
  function clear(){
    
  }

}