<?php
namespace controller;

require_once __DIR__ . ('/../util/TrelloApiUtil.php');

use Slim\Http\Request;
use Slim\Http\Response;
use util\TrelloApiUtil;

class LoginController
{
  public function login(Request $request, Response $response){
    // TemporaryCredentialの発行
    $temporaryCredentials = TrelloApiUtil::getRequestTemporaryCredentials();
    // セッションにTemporaryCredentialを入れる
    $_SESSION['temporary_credentials'] = serialize($temporaryCredentials);
    // 認証用のURLにアプリの名前を付与
    $authorizationUri = TrelloApiUtil::getBuildAuthorizationUri($temporaryCredentials ,"testApi");
    // trelloの認証サイトにリダイレクト
    header("Location: {$authorizationUri}");
    exit();
  }

  public function auth_callback(Request $request, Response $response){
    $responseCotentsFromTrello = null;
    // セッションにすでにtokenが設定してある。
    if (isset($_SESSION['token_credentials'])) {
      $tokenCredentials = unserialize($_SESSION['token_credentials']);
      // 自ユーザー情報を取得
      $responseCotentsFromTrello = TrelloApiUtil::sendRequest($tokenCredentials, 'GET', 'members/me');
    // GETの引数でoauth_tokenとoauth_verifierがある
    } else if (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])) {
      // TemporaryCredentialを取得してsessionから破棄
      $temporaryCredentials = unserialize($_SESSION['temporary_credentials']);
      unset($_SESSION['temporary_credentials']); 
      // tokenを作成しセッションへ格納
      $tokenCredentials = TrelloApiUtil::getRequestTokenCredentials($temporaryCredentials, $_GET['oauth_token'], $_GET['oauth_verifier']);
      $_SESSION['token_credentials'] = serialize($tokenCredentials);
      // 自ユーザー情報を取得
      $responseCotentsFromTrello = TrelloApiUtil::sendRequest($tokenCredentials, 'GET', 'members/me');
    // それ以外はエラー
    } else {
      header("Content-Type: application/json; charset=utf-8");
      return $response->withJson(["message" => "Authentication_NG"], 401);
    }
    // ユーザ情報のjsonを文字列化
    $encoded_json = mb_convert_encoding($responseCotentsFromTrello, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
    // ユーザ名を取得してセッションへ
    $username = TrelloApiUtil::get_username($encoded_json);
    $_SESSION['username'] = serialize($username);
    // fullnameを取得
    $fullName = TrelloApiUtil::get_fullName($encoded_json);
    header("Content-Type: application/json; charset=utf-8");
    return $response->withJson(["username" => $username, "fullName" => $fullName], 200);
  }
}