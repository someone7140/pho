<?php
namespace App\Http\Response\Common;

class CommonResponse
{
     public $status;
     public $data;
     public $errors;
 
     public function returnResponse() {
          return json_encode($this, JSON_UNESCAPED_UNICODE);
     }

     public function returnStatusOnly($status) {
          $this->status = $status;
          return $this->returnResponse();
     }

     public function returnErrorWithMessage($status, $message) {
          $this->status = $status;
          $this->errors = new \stdClass();
          $this->errors->message = $message;
          return $this->returnResponse();
     }

     public function returnErrorWithMessageNonJsonEncode($status, $message) {
          $this->status = $status;
          $this->errors = new \stdClass();
          $this->errors->message = $message;
          return $this;
     }
}
