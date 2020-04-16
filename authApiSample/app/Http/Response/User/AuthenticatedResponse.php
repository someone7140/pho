<?php
namespace App\Http\Response\User;

use App\Http\Response\Common\CommonResponse;

class AuthenticatedResponse extends CommonResponse
{
     public function authenticatedResponse($userId, $userName, $role, $token) {
          $this->status = config('const_http_status.OK_200');
          $this->data = new \stdClass();
          $this->data->user_id = $userId;
          $this->data->user_name = $userName;
          $this->data->role = $role;
          $this->data->token = $token;
          return $this->returnResponse();
     }
}
