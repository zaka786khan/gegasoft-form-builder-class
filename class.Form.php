<?php
class Form {
  public static function __callStatic($method, $args) {
    if(substr($method,-6)=='_field'){
      $type = substr($method,0,-6);
      return self::input($type, ...$args);
    } else {
      return self::input($method, ...$args);
    }
  }

  public static function wrap($wrapper, ...$elements){
    return $_output = str_replace('...', implode('',$elements), $wrapper);
  }

  public static function create($attr, ...$elements){
    return self::element('form', $attr, ...$elements);
  }

  public static function element($type, $attr='', ...$elements){
    $attr = self::_clean_attr($attr);
    return "<$type $attr>" . implode('',$elements) . "</$type>";
  }

  public static function input($type, $name, $value='', $attr='', ...$elements){
    $attr = self::_clean_attr($attr);
    switch($type) {
      case 'label':
        return "<label for='$name' $attr>$value</label>" . implode('',$elements);
      case 'text':
        return "<input type='text' name='$name' value='$value' $attr />" . implode('',$elements);
      case 'submit':
        return "<input type='submit' name='$name' value='$value' $attr />" . implode('',$elements);
      case 'password':
        return "<input type='password' name='$name' value='$value' $attr />" . implode('',$elements);
      case 'email':
        return "<input type='email' name='$name' value='$value' $attr />" . implode('',$elements);
      case 'hidden':
        return "<input type='hidden' name='$name' value='$value' $attr />" . implode('',$elements);
      case 'option':
        return "<option value='$value' $attr>$name</option>";
      case 'select':
        return "<select name='$name' $attr>" . implode('',$elements);
      case 'file':
        return "<input type='file' name='$name' value='$value' $attr />" . implode('',$elements);
      case 'radio':
        return "<input type='radio' name='$name' value='$value' $attr />" . implode('',$elements);
      case 'checkbox':
        return "<input type='checkbox' name='$name' value='$value' $attr />" . implode('',$elements);
      case 'textarea':
        return "<textarea name='$name' $attr>$value</textarea>" . implode('',$elements);
      case 'date':
        return "<input type='date' name='$name' value='$value' $attr />" . implode('',$elements);
      case 'number':
      case 'numeric':
        return "<input type='number' name='$name' value='$value' $attr />" . implode('',$elements);
      case 'datetime':
      case 'datetime-local':
        return "<input type='datetime-local' name='$name' value='$value' $attr />" . implode('',$elements);
      default:
        return self::element($type, $name, $value, $attr, ...$elements);
    }
  }

  public static function validate($rules, $posted_data) {
    $errors = [];
    foreach($rules as $key => $value){
      if(isset($posted_data[$key])){
        if($error = self::_validate_rules($posted_data[$key], ((array)$value)[0])) {
          $errors[] = ((array)$value)[1]??$error;
        }
      }
    }
    return $errors;
  }

  private static function _clean_attr($attr){
    $attr = explode(',',trim(trim($attr),','));
    array_walk($attr, function(&$item){
      if(strpos($item,'=')>0){
        $item = str_replace('=','="',$item) . '"';
      }
    });
    return $attr = implode('',$attr);
  }
  private static function _validate_rules($str, $rules) {
    $errors = [];
    foreach(explode("|",$rules) as $r){
      if($error = self::valid($str, $r)){
        $errors[] = $error;
      }
    }
    return implode(',' , $errors);
  }

  private static function _validate_rule($str, $rule){
    if($rule=="integer"){
      if(preg_match('/^[0-9]+$/',$str))
        return false;
      else
        return "Integer";
    }elseif(substr($rule,0,1) == "="){
      if($str == substr($rule,1))
        return false;
      else 
        return "=".substr($rule,1);
    }elseif($rule=="decimal"){
      if(preg_match('/^[0-9.]+$/',$str))
        return false;
      else
        return "Decimal";
    }elseif($rule=="alpha"){
      if(preg_match('/^[a-zA-Z]+$/',$str))
        return false;
      else
        return "A to Z";
    }elseif($rule=="alphanum"){
      if(preg_match('/^[a-zA-Z0-9]+$/',$str))
        return false;
      else
        return "Alpha Numeric";
    }  elseif($rule=="email"){
      if(preg_match('/^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/',$str)) 
        return false;
      else
        return "Email Address";
    } elseif($rule=="ip"){
      if(preg_match('/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/',$str))
        return false;
      else
        return "IP Address";
    }  elseif($rule=="url"){
      if(preg_match('/^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$/',$str))
        return false;
      else
        return "URL Address";
    }  elseif($rule=="hex"){
      if(preg_match('/^#?([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?$/',$str))
        return false;
      else
        return "HEX Number";
    }  elseif($rule=="float"){
      if(preg_match('/^[-+]?[0-9]+[.]?[0-9]*([eE][-+]?[0-9]+)?$/',$str))
        return false;
      else
        return "Float Numbers";
    }  elseif($rule=="name"){
      if(preg_match('/^[a-zA-Z]+(([\',. -][a-zA-Z ])?[a-zA-Z]*)*$/',$str))
        return false;
      else
        return "Valid Name";
    }  elseif($rule=="mac"){
      if(preg_match('/^([0-9a-fA-F][0-9a-fA-F]:){5}([0-9a-fA-F][0-9a-fA-F])$/',$str))
        return false;
      else
        return "MAC Address";
    }  elseif($rule=="creditcard"){
      if(preg_match('/^((4\d{3})|(5[1-5]\d{2})|(6011)|(7\d{3}))-?\d{4}-?\d{4}-?\d{4}|3[4,7]\d{13}$/',$str))
        return false;
      else
      return "Credit Card Number";
    }  elseif($rule=="safetext"){
      if(preg_match('/^[a-zA-Z0-9 .\-_,!]+$/',$str))
        return false;
      else
        return "Safe Text (A-Z a-z 0-9 .-_,!)";
    }  elseif($rule=="english"){
      if(preg_match('/^[ -~]+$/',$str))
        return false;
      else
        return "English Letters";
    }  elseif($rule=="fullurl"){
      if(preg_match('/^((((https?|ftps?|gopher|telnet|nntp):\/\/)|(mailto:|news:))(%[0-9A-Fa-f]{2}|[-()_.!~*\';\/?:@&=+$A-Za-z0-9])+)([).!\';\/?:][[:blank:]])?$/',$str))
        return false;
      else
        return "Full URL Address";
    }  elseif($rule=="image"){
      if(preg_match('/\w+\.(gif|png|jpg|jpeg)$/i',$str))
        return false;
      else
        return "Image File (gif | png | jpg | jpeg)";
    }  elseif(substr($rule,0,3) == "lt:"){
      if(strlen($str) <= substr($rule,3))
        return false;
      else
        return "String Length <= ".substr($rule,3)."";
    }  elseif(substr($rule,0,3) == "gt:"){
      if(strlen($str) >= substr($rule,3))
        return false;
      else
        return "String Length >= ".substr($rule,3)."";
    }  elseif(substr($rule,0,3) == "eq:"){
      if(strlen($str) === substr($rule,3))
        return false;
      else
        return "String Length of ".substr($rule,3)."";
    }  elseif($rule=="required"){
      if(strlen($str) > 0 )
        return false;
      else
        return "NonEmpty";
    }  elseif($rule=="mmddyy"){
      if(preg_match('^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$',$str))
        return false;
      else
        return "mmddyy";
    }  elseif(substr($rule,0,4) == "regx"){
      if(preg_match('/^('.$rule.')+$/',substr($rule,4)))
        return false;
      else
        return "Regx Pattern (".substr($rule,4).")";
    } else
    return "Invalid Rule";
  }
}
?>