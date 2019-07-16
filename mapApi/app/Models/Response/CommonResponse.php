<?php
namespace App\Models\Response;

class CommonResponse
{
     public $status;
     public $message;

     public function return_response() {
          return json_encode($this, JSON_UNESCAPED_UNICODE);
     }
}
