<?php

$message = ["text" => "Slack APIメッセージ投稿テストです。"]; //連想配列で入れる。変数はスラックで指定されているものを使う。

$ch = curl_init("https://hooks.slack.com/services/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"); //webhookのurlをcurlで設定
curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($message));
curl_setopt( $ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, false ); //基本はtrueで
curl_exec($ch);
// error_log(curl_error($ch));
curl_close($ch);　//セッションのクローズ忘れない
