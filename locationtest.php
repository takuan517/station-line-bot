<?php

$accessToken = 'YOUR_ACCESS_TOKEN';

$return_message_text_a="";


//ユーザーからのメッセージ取得
$json_string = file_get_contents('php://input');
$json_object = json_decode($json_string);

//取得データ
$replyToken = $json_object->{"events"}[0]->{"replyToken"};        //返信用トークン
$message_type = $json_object->{"events"}[0]->{"message"}->{"type"};    //メッセージタイプ
$message_text = $json_object->{"events"}[0]->{"message"}->{"text"};    //メッセージ内容
$latitude = $json_object->{"events"}[0]->{"message"}->{"latitude"};
$longitude = $json_object->{"events"}[0]->{"message"}->{"longitude"};



if($message_type=="location"){

    function sending_locationmessages($accessToken, $replyToken, $message_type, $return_message_text_a){
      //レスポンスフォーマット
      $response_format_text = [
          "type" => 'text',
          "text" => $return_message_text_a
      ];
      //ポストデータ
      $post_data = [
          "replyToken" => $replyToken,
          "messages" => [$response_format_text]
      ];
      //curl実行
      $ch = curl_init("https://api.line.me/v2/bot/message/reply");
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json; charser=UTF-8',
          'Authorization: Bearer ' . $accessToken
      ));
      $result = curl_exec($ch);
      curl_close($ch);
    }

  $xml = simplexml_load_file('http://map.simpleapi.net/stationapi?x='.$longitude.'&y='.$latitude.'&output=json');
  $json = json_decode(file_get_contents('http://map.simpleapi.net/stationapi?x='.$longitude.'&y='.$latitude.'&output=json'), TRUE);

  $return_message_text_a = $json[0]["name"]."です\n"."\n地図はこちら https://www.google.co.jp/maps/search/".$json[0]["name"]."\n";

  sending_locationmessages($accessToken, $replyToken, $message_type, $return_message_text_a);
}

function sending_messages($accessToken, $replyToken, $message_type, $return_message_text){
  //レスポンスフォーマット
  $response_format_text = [
      "type" => $message_type,
      "text" => $return_message_text
  ];

  //ポストデータ
  $post_data = [
      "replyToken" => $replyToken,
      "messages" => [$response_format_text]
  ];

  //curl実行
  $ch = curl_init("https://api.line.me/v2/bot/message/reply");
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json; charser=UTF-8',
      'Authorization: Bearer ' . $accessToken
  ));
  $result = curl_exec($ch);
  curl_close($ch);
}
