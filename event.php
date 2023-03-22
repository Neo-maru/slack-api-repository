<?php

$data = file_get_contents('php://input');　//外部からのアクセスを受けて起動。クライアントから投げられたものを受け取る
$data = json_decode($data, true); //JSON形式なのでphpにデコード

if ($data['type']=='url_verification') {　　//slackが定めているもの。チャレンジ認証。
    echo json_encode($data["challenge"]);　//phpだとこのやり取りだけで簡単に認証できる。コピペ推奨
} else if ($data['type']=='event_callback') {　//ここからイベント処理
    // error_log(print_r($data, true));
    $event = $data["event"];  //イベントの内容を入れている
    // error_log(print_r($event, true));

    if ($event["type"]=="app_mention") {  //イベントがapp_mention（スラックのEvent Scription参考）なら…以下を実装。アクションが複数ある場合はifで分岐させる。
        error_log("app_mentioned");
        $message = 
        [
            'attachments' => 
            [
                [
                    "text" => "以下の選択肢から要件を選んでください。",
                    "callback_id" => "select_menu",
                    "color" => "#3AA3E3",
                    "attachment_type" => "default",
                    "actions" => [
                        [
                            "name" => "list",
                            "text" => "選択してください",
                            "type" => "select",
                            "options" => [
                                [
                                    "text" => "WPについて知りたい",
                                    "value" => "wpHomepageLink"
                                ],
                                [
                                    "text" => "社内の管理画面にアクセスしたい",
                                    "value" => "wpManageSystem"
                                ],
                            ]
                        ],
                        [
                            "type" => "button",
                            "text" => "キャンセル",
                            "value" => "cancel"
                        ],
                    ]
                ],
            ]
        ];
    
        $ch = curl_init("https://hooks.slack.com/services/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");
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($message));
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, false );
        curl_exec($ch);
        // error_log(curl_error($ch));
        curl_close($ch);
    }
}
