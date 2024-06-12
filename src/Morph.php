<?php

namespace Tsumari\Gooapi;

class Morph
{
    private static $goo_hiragana_api_url = 'https://labs.goo.ne.jp/api/morph';

    /**
     * 形態素解析を行うメソッド
     *
     * @param string $goo_app_id Goo APIのアプリケーションID
     * @param string $sentence 解析対象テキスト
     * @return array 形態素リスト
     */
    public static function read($goo_app_id = "", $sentence = "", $hiragana = true)
    {
        if ($goo_app_id === "" || $sentence === "") {
            return [[[$sentence]]];
        }

        $params = [
            'app_id' => $goo_app_id,
            'sentence' => $sentence,
            'info_filter' => 'read',
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

        if (is_array($response) && array_key_exists('word_list', $response) && isset($response['word_list'])) {
            if ($hiragana) {
                return self::convertKatakanaToHiragana($response['word_list']);
            } else {
                return $response['word_list'];
            }
        } else {
            return [[[$sentence]]]; // そのまま返す
        }
    }

    /**
     * 与えられた多次元配内列のカタカナ文字列をひらがなに変換して返却する
     *
     * @param array $array カタカナを含む配列
     * @return array カタカナをひらがなに変換した配列
     */
    private static function convertKatakanaToHiragana(array $array)
    {
        return array_map(function ($item) {
            if (is_array($item)) {
                return self::convertKatakanaToHiragana($item); // 再帰的に処理を適用
            } else {
                return mb_convert_kana($item, 'c'); // カタカナをひらがなに変換
            }
        }, $array);
    }
}
