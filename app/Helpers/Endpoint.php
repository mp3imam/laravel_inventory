<?php

namespace App\Helpers;
 
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Request;
use Session;

class Endpoint {

    public static function endpoint()
    {
        $endpoint = "http://192.168.42.225:3090/v1/";
        return $endpoint;
    }

    public static function reqGet($url)
    {
        $token = Session::get('token');

        $client = new Client();
        $res = $client->request('GET', $url,[
            'headers'    => [
                
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
            
            ],
        ]);

        if($res->getStatusCode() == 200) {
            return json_decode($res->getBody());
        }
        else {
            return response()->json(['status' => false , 'message' => 'Check Your Code !']);
        }
    }

    public static function reqPost($url, $param, $header)
    {
        
        $client = new Client();
        $res = $client->request('POST', $url, [
            'headers'    => $header,
            'form_params' => $param,
        ]);
        
        if($res->getStatusCode() == 200) {
            return json_decode($res->getBody());
        }
        else {
            return response()->json(['status' => false , 'message' => 'Check Your Code !']);
        }
    }

    public static function reqPut($url, $param, $header)
    {
        $client = new Client();
        $res = $client->request('PUT', $url, [
            'headers'    => $header,
            'form_params' => $param,
        ]);
        
        if($res->getStatusCode() == 200) {
            return json_decode($res->getBody());
        }
        else {
            return response()->json(['status' => false , 'message' => 'Check Your Code !']);
        }
    }

    public static function reqDestroy($url, $param)
    {
        $token = Session::get('token');

        $client = new Client();
        $res = $client->request('DELETE', $url, [
            'headers'    => [
                
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
        
            ],
            'form_params' => $param,
        ]);
        
        if($res->getStatusCode() == 200) {
            return json_decode($res->getBody());
        }
        else {
            return response()->json(['status' => false , 'message' => 'Check Your Code !']);
        }
    }
    
}