<?php

/**
 * フォーム描画 & エラーチェックを行うライブラリ
 * 
 * インスタンスを生成したあと、$mVF->itemlist_arrで項目一覧の日本語ラベルを指定する必要がある。
 * 送信されたフォームのデータは以下のフォーマットで整形される
 *   info_arr =>Array(
 *     [$key] => Array(
 *       ["value"] => 送信されたvalue
 *       ["label"] => 数値ラジオボタンの場合の日本語ラベル
 *       ["error"] => エラーだった場合のエラーメッセージ
 *       ["error_class"] => "error" エラーだった場合
 *     )
 *   )
 * 
 * @package chkForm
 * @see http://
 * @author tsujimoto tomoyuki
 * @copyright Copyright &copy; 2012 tsujimoto tomoyuki
 * @date 2012/01/16
 * @version $Id: cfViewForm.php,v 1.02 2012/01/16 11:00:00 danielc Exp $
 */
class chkForm{

  /*
   * エラーチェックの後、最終的にOK/NGを判定するためのもの
   * @var boolean
   */
  public $chk_errorflg = false;
  
  /*
   * フォーム項目のラベル対応表
   * @var array
   */
  public $itemlist_arr = array();
/*
例
  $mVF->itemlist_arr = array(
    "name01"=> "お名前(姓)",
    "name02"=> "お名前(名)",
    "kana01"=> "お名前フリガナ(姓)",
    "kana02"=> "お名前フリガナ(名)"
  );
*/

  public function __construct(){
  }


  /* !FUNCTION *************************************** */
  
  
  /**
   * 配列か連想配列かどうかを判別する。
   * 
   * 渡された配列のキーが0からの連番になっているかどうかで判別させる。
   * 
   * @return boolean 連想配列だったらTRUEを返す
   */
  public function is_hash(&$array){
    $i = 0;
    foreach($array as $k => $dummy) {
      if ( $k !== $i++ ) return true;
    }
    return false;
  }
  


  /* !ERROR_CHECK *************************************** */

  /**
   * 渡された文字列の先頭、末尾の空白要素を削除。
   * 
   * @parm string $str トリム対象の文字列
   * @pram string $trim トリムパターン( デフォルト：// 半角/改行/タブ/全角/)
   * @return string トリム後の文字列
   */
  public function tr($str ,$trim = "\s　"){
    $patterns = "/^[" . $trim . "]+|[" . $trim . "]+$/u";
    $str = preg_replace($patterns, "", $str);
    return $str;
  }

  /**
   * 渡された配列の要素の先頭、末尾の空白要素を削除。
   * 
   * @parm array $arr トリム対象の配列
   * @pram string $match  トリムパターン( デフォルト：// 半角/改行/タブ/全角/)
   * @return array トリム後の配列
   */
  public function trArr($arr ,$trim = "\s　"){
    // 文頭、文末の空要素を削除。半角/全角/改行/タブ/Nullバイト
    $patterns = "/^[" . $trim . "]+|[" . $trim . "]+$/u";
    foreach($arr as $key => $val){
      $arr[$key] = preg_replace($patterns,"",$arr[$key]);
//    $arr[$key] = trim($arr[$key], " 　\t\r\n\0"); //trim関数だとマルチバイトで文字化けを起こす。パターンは/半角スペース/全角スペース/タブ/改行/Nullバイト
    }
    return $arr;
  }



  /**
   * 必須項目が入力されているかどうかチェックする。
   * 
   * @param array &$info_arr 入力されたデータ一式
   * @param array $chklist_arr チェックするキーをまとめた配列
   */
  public function chkErrRequire(&$info_arr,$chklist_arr){
    foreach($chklist_arr as $key){
      if(!isset($info_arr[$key]['value']) || trim($info_arr[$key]['value']) == ""){
        $info_arr[$key]['error'] .= $this->itemlist_arr[$key]."は必須です。<br />";
        $info_arr[$key]['error_class'] .= " error ";
        $this->chk_errorflg = true;
      }
    }
  }

  /**
   * メールアドレスが正しいかどうかチェック
   * 
   * @param array &$info_arr 入力されたデータ一式
   * @param array $chklist_arr チェックするキーをまとめた配列
   */
  public function chkErrEmail(&$info_arr,$chklist_arr){
    foreach($chklist_arr as $key){
      if(!isset($info_arr[$key]['value']) || trim($info_arr[$key]['value']) == ""){
      }else{
        //入力チェック
        if(!ereg("^[^@]+@[^.^@]+\..+", $info_arr[$key]['value']) ) {
          $info_arr[$key]['error'] .= $this->itemlist_arr[$key] . "の形式が不正です。<br />";
          $info_arr[$key]['error_class'] .= " error ";
          $this->chk_errorflg = true;
        }
        if(!ereg("^[a-zA-Z0-9_\.@\+\?-]+$", $info_arr[$key]['value']) ) {
          $info_arr[$key]['error'] .= $this->itemlist_arr[$key] . "に使用する文字を正しく入力してください。<br />";
          $info_arr[$key]['error_class'] .= " error ";
          $this->chk_errorflg = true;
        }
      }
    }
  }

  /**
   * 数値かどうかチェック
   * 
   * @param array &$info_arr 入力されたデータ一式
   * @param array $chklist_arr チェックするキーをまとめた配列
   */
  public function chkErrNum(&$info_arr,$chklist_arr){
    foreach($chklist_arr as $key){
      if(!isset($info_arr[$key]['value']) || trim($info_arr[$key]['value']) == ""){
      }else{
        //入力チェック
        if(!ereg("^[0-9]+$", $info_arr[$key]['value']) ) {
          $info_arr[$key]['error'] .= $this->itemlist_arr[$key] . "は半角数字で入力してください。<br />";
          $info_arr[$key]['error_class'] .= " error ";
          $this->chk_errorflg = true;
        }
      }
    }
  }

  /**
   * 入力された値が空でないかチェックする
   * 
   * @pram string $val  チェック対象
   * @return boolean 空だったらTRUEを返す
   */
  function chkErrEmpty($val){
    if( !isset($val) ){ $this->chk_errorflg = true; }
    elseif( $val === "" ){ $this->chk_errorflg = true; }
    elseif( preg_match("/^-+$/", $val) ){ $this->chk_errorflg = true; }
  }

} //end class chkForm

