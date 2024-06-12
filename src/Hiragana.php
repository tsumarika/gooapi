<?php

namespace Tsumari\Gooapi;

class Hiragana
{
    private static $goo_hiragana_api_url = 'https://labs.goo.ne.jp/api/hiragana';

    /**
     * ひらがな変換を行うメソッド
     *
     * @param string $goo_app_id Goo APIのアプリケーションID
     * @param string $sentence 変換する文字列
     * @return string 変換されたひらがなの文字列
     */
    public static function convert($goo_app_id = "", $sentence = "")
    {
        if ($goo_app_id === "" || $sentence === "") {
            return $sentence;
        }

        $params = [
            'app_id' => $goo_app_id,
            'sentence' => $sentence,
            'output_type' => 'hiragana',
        ];
        $json_params = json_encode($params);
        $opts = array(
            'http' => array(
                'method' => "POST",
                'header' => "Accept: application/json\r\n" .
                "Content-Type: application/json\r\n",
                'content' => $json_params,
            ),
        );
        $context = stream_context_create($opts);

        $json_response = file_get_contents(self::$goo_hiragana_api_url, false, $context);
        $response = json_decode($json_response, true);

        if (is_array($response) && array_key_exists('converted', $response) && isset($response['converted'])) {
            return $response['converted'];
        } else {
            return $sentence; // そのまま返す
        }
    }
}
