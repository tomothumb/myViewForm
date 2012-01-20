<?php

/**
 * 長い文章を省略して表示する
 * 
 * @param string $str 省略する文字列
 * @param integer $end  省略開始位置
 * @return string 省略後
 */
function outputAbbr($str,$end=50){
  return nl2br(mb_substr( htmlspecialchars($str) ,0,$end) . "..." );
}

/*
 * htmlエンティティに変換して返す
 * @param string $str 変換する文字列
 * @return string 省略後
 */
function h($str){
  return nl2br(htmlspecialchars($str));
}


/** 
 * 渡された文字列の先頭、末尾の空白要素を削除。
 * 
 * @parm string $str トリム対象の文字列
 * @pram string $trim トリムパターン( デフォルト：// 半角/改行/タブ/全角/)
 * @return string トリム後の文字列
 */
function tr($str ,$trim = "\s　"){
  $patterns = "/^[" . $trim . "]+|[" . $trim . "]+$/u";
  $str = preg_replace($patterns, "", $str);
  return $str;
}

/* 
 * 渡された配列の要素の先頭、末尾の空白要素を削除。
 * 
 * @parm array $arr トリム対象の配列
 * @pram string $match トリムパターン( デフォルト：// 半角/改行/タブ/全角/)
 * @return string トリム後の配列
 */
function trArr($arr ,$trim = "\s　"){
  // 文頭、文末の空要素を削除。半角/全角/改行/タブ/Nullバイト
  $patterns = "/^[" . $trim . "]+|[" . $trim . "]+$/u";
  foreach($arr as $key => $val){
    $arr[$key] = preg_replace($patterns,"",$arr[$key]);
//    $arr[$key] = trim($arr[$key], " 　\t\r\n\0"); //trim関数だとマルチバイトで文字化けを起こす。パターンは/半角スペース/全角スペース/タブ/改行/Nullバイト
  }
  return $arr;
}
