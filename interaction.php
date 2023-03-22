<?php

$request = [];
// REQUESTのjsonが多層の場合に対応
foreach ($_REQUEST as $key => $value) {
    $request[$key] = json_decode($value, true);

    // json_decodeはクォートされていない文字列がnullになるので戻す
    if ($request[$key] == null) {
        $request[$key] = $value;
    }
}

error_log(print_r($request, true));
$payload = $request["payload"];
$original_message = $payload["original_message"];

if ($payload["callback_id"]=="select_menu") {
    $selected_option = $payload["actions"][0]["selected_options"][0]["value"];
    $response_url = $payload["response_url"];

    if ($selected_option == "wpHomepageLink") {
        $original_message["attachments"][0]["text"] = "要件: WPについて知りたい。";
        $original_message["attachments"][0]["actions"] = null;　//いらないものはnullにしていく
        $adding_message = [
            "color" => "3AA3E3",
            "fallback" => "homepage link",
            "text" => "WPについて知りたいなら、ホームページを見るのが良いと思います。\nWPのホームページのリンクは以下です。\nhttps://withpassion.co.jp/",
        ];
        array_push($original_message["attachments"], $adding_message);

        $ch = curl_init($response_url);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($original_message));
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // falseにすると、curlの結果が送ったメッセージに上書きされてしまう。インタラクション内では必ずtrue
        curl_exec($ch);
        curl_close($ch);
    } else if ($selected_option == "wpManageSystem") {
        $original_message["attachments"][0]["text"] = "要件: WPの管理画面にアクセスしたい。";
        $original_message["attachments"][0]["actions"] = null;
        $adding_message = [
            "color" => "3AA3E3",
            "fallback" => "manage system link",
            "text" => "WPの管理画面のリンクは以下です。\n・CRM: http://crm.senmonka-kensaku.com/ \n・営業管理ツール: http://slman.senmonka-kensaku.com/ \n・プラスの管理画面: http://plus-admin.senmonka-kensaku.com/ \n・除外管理画面: http://nkman.senmonka-kensaku.com/",
        ];
        array_push($original_message["attachments"], $adding_message);

        $ch = curl_init($response_url);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($original_message));
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_exec($ch);
        curl_close($ch);
    } else {
        error_log("予期せぬボタンが押された可能性があります。");
    }
} else {
    error_log("予期せぬエラーが発生しました。");
}
