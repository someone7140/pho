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
    // セッションにすでにtokenが設定してある。
    if (isset($_SESSION['token_credentials'])) {
      $tokenCredentials = unserialize($_SESSION['token_credentials']);
      // 自ユーザー情報を取得
      $response = TrelloApiUtil::sendRequest($tokenCredentials, 'GET', 'members/me');
    // GETの引数でoauth_tokenとoauth_verifierがある
    } else if (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])) {
      // TemporaryCredentialを取得してsessionから破棄
      $temporaryCredentials = unserialize($_SESSION['temporary_credentials']);
      unset($_SESSION['temporary_credentials']); 
      // tokenを作成しセッションへ格納
      $tokenCredentials = TrelloApiUtil::getRequestTokenCredentials($temporaryCredentials, $_GET['oauth_token'], $_GET['oauth_verifier']);
      $_SESSION['token_credentials'] = serialize($tokenCredentials);
      // 自ユーザー情報を取得
      $response = TrelloApiUtil::sendRequest($tokenCredentials, 'GET', 'members/me');
    // それ以外はエラー
    } else {
      $response = $response->withJson(["message" => "Authentication_NG"], 401);
    }
    header("Content-Type: application/json; charset=utf-8");
    return $response;
  }
}