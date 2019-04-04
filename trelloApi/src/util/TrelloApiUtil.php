<?php
namespace util;

use Risan\OAuth1\ProviderFactory;
use Risan\OAuth1\Credentials\TemporaryCredentials;
use Risan\OAuth1\Credentials\TokenCredentials;

require_once __DIR__ . ('/../constants/AuthenticationConstants.php');

class TrelloApiUtil
{
  // trello用のoauth1
  private static function getOauth1(){
    $oauth1 = ProviderFactory::trello([
      'client_credentials_identifier' => TRELLO_API_KEY,
      'client_credentials_secret' => TRELLO_SECRET,
      'callback_uri' => CALL_BACK_URI_FROM_TRELLO,
    ]);
    return $oauth1;
  }

  // 一時署名の取得
  public static function getRequestTemporaryCredentials(){
    return self::getOauth1()->requestTemporaryCredentials();
  }

  // 認証用URLの取得
  public static function getBuildAuthorizationUri(TemporaryCredentials $temporaryCredentials, String $app_name){
    return self::getOauth1()->buildAuthorizationUri($temporaryCredentials) . "&name=" . $app_name;
  }

  // tokenの取得
  public static function getRequestTokenCredentials(TemporaryCredentials $temporaryCredentials, String $oauth_token, String $oauth_verifier){
    return self::getOauth1()->requestTokenCredentials($temporaryCredentials, $oauth_token, $oauth_verifier);
  }

  // リクエストの送信
  public static function sendRequest(TokenCredentials $tokenCredentials, String $type, String $path){
    $response = self::getOauth1()->setTokenCredentials($tokenCredentials)->request($type, $path);
    return $response->getBody()->getContents();
  }

  // membersのjsonからusernameを取得
  public static function get_username(String $members_json){
    $arr = json_decode($members_json);
    return $arr->username;
  }
  // membersのjsonからfullNameを取得
  public static function get_fullName(String $members_json){
    $arr = json_decode($members_json);
    return $arr->fullName;
  }
}