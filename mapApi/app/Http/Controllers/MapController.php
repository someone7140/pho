<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\Response\Address;

class MapController
{
    public function script()
    {
        $curl = curl_init(config('const_api.YAHOO_MAP_JS_DOMAIN') . config('const_api.YAHOO_API_KEY'));
        // リクエストのオプションをセットしていく
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET'); // メソッド指定
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 証明書の検証を行わない
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // レスポンスを文字列で受け取る

        // レスポンスを変数に入れる
        $response = curl_exec($curl);

        // curlの処理を終了
        curl_close($curl);
        return $response;
    }
    public function address(Request $request)
    {
        $lat = $request->lat;
        $lon = $request->lon;
        $curl = curl_init(config('const_api.FINDS_JP_DOMAIN') . $lat . "&lon=" . $lon);
        // リクエストのオプションをセットしていく
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET'); // メソッド指定
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 証明書の検証を行わない
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // レスポンスを文字列で受け取る

        // レスポンスを変数に入れる
        $response = curl_exec($curl);
        $responseJson = json_decode($response);
        // Json用のオブジェクト
        $resultJson = new Address();
        $resultJson->status = $responseJson->status;
        if ($resultJson->status == config('const_http_status.OK_200')) {
            $address = "";
            if(!empty($responseJson->result->prefecture)) {
                $address = $address . $responseJson->result->prefecture->pname;
            }
            if(!empty($responseJson->result->municipality)) {
                $address = $address . $responseJson->result->municipality->mname;
            }
            if(!empty($responseJson->result->local[0])) {
                $address = $address . $responseJson->result->local[0]->section;
            }
            $resultJson->address = $address;
        } else {
            $resultJson->message = "住所の取得に失敗しました。";
        }
        return $resultJson->return_response();
    }
}
