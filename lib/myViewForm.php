<?php

/**
 * フォーム描画 & エラーチェックを行うライブラリ
 * 
 * 
 * @author tsujimoto tomoyuki
 * @date 2012/01/16
 * @version $Id: myViewForm.php,v 1.02 2012/01/16 11:00:00 danielc Exp $
 * @use
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
 **/
class myViewForm{

  /*
   * エラーチェックの後、最終的にOK/NGを判定するためのもの
   * @type boolean
   */
  public $chk_errorflg = false;
  
  /*
   * フォーム項目のラベル対応表
   * @type array
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


  # 都道府県
  public $prevlist_num_arr = array(
    "1"=>"北海道",
    "2"=>"青森県",
    "3"=>"岩手県",
    "4"=>"宮城県",
    "5"=>"秋田県",
    "6"=>"山形県",
    "7"=>"福島県",
    "8"=>"茨城県",
    "9"=>"栃木県",
    "10"=>"群馬県",
    "11"=>"埼玉県",
    "12"=>"千葉県",
    "13"=>"東京都",
    "14"=>"神奈川県",
    "15"=>"新潟県",
    "16"=>"富山県",
    "17"=>"石川県",
    "18"=>"福井県",
    "19"=>"山梨県",
    "20"=>"長野県",
    "21"=>"岐阜県",
    "22"=>"静岡県",
    "23"=>"愛知県",
    "24"=>"三重県",
    "25"=>"滋賀県",
    "26"=>"京都府",
    "27"=>"大阪府",
    "28"=>"兵庫県",
    "29"=>"奈良県",
    "30"=>"和歌山県",
    "31"=>"鳥取県",
    "32"=>"島根県",
    "33"=>"岡山県",
    "34"=>"広島県",
    "35"=>"山口県",
    "36"=>"徳島県",
    "37"=>"香川県",
    "38"=>"愛媛県",
    "39"=>"高知県",
    "40"=>"福岡県",
    "41"=>"佐賀県",
    "42"=>"長崎県",
    "43"=>"熊本県",
    "44"=>"大分県",
    "45"=>"宮崎県",
    "46"=>"鹿児島県",
    "47"=>"沖縄県"
  );

  # 日付（年/月/日）
  public $yearlist_num_arr = array();
  public $monthlist_num_arr = array();
  public $daylist_num_arr = array();

  # 性別
  public $sexlist_num_arr = array(
    "1"=>"男性" , 
    "2"=>"女性"
  );

  # メルマガ申し込み
  public $mailmaga_flglist_num_arr = array(
    "1"=>"HTMLメール＋テキストメールを受け取る" , 
    "2"=>"テキストメールを受け取る" , 
    "3"=>"受け取らない"
  );

  public function __construct(){
    //年
    $this->setYear( $this->yearlist_num_arr );
    //月
    for($i=1;$i<=12;$i++){
      $this->monthlist_num_arr[$i] = $i;
    }
    //日
    for($i=1;$i<=31;$i++){
      $this->daylist_num_arr[$i] = $i;
    }

  }


  /* !FUNCTION *************************************** */

  /* !SETTING *************************************** */

  /**
   * 年度の配列を再設定する。
   * @parm $start(num) 開始年度
   * @parm $end(num) 終了年度
   * @return none;
   **/
  public function setYear(&$arr, $start = 2000, $end = ""){
    if($end == ""){ $end = date("Y"); }
    $this->setNumOption($arr, $start, $end);
  }

  /**
   * 年度の配列を再設定する。
   * @parm $start(num) 開始年度
   * @parm $end(num) 終了年度
   * @return none;
   **/
  public function setNumOption(&$arr, $start = "", $end = ""){
    if($start >= 0 && $end >= 0 && $start <= $end ){
      $arr = array();
      for($i = $start; $i <= $end; $i++){
        $arr[$i] = $i;
      }
    }
  }

  /* !VIEW *************************************** */

  /**
   * 都道府県のセレクトボックスを描画
   * @parm $value(string) 送信された値
   * @return none;
   **/
  public function viewPref($value = ""){
    $output = "";
    $this->viewSelectOption($this->prevlist_num_arr,$value);
  }
  

  
  /**
   * 年度のセレクトボックスを描画
   * @parm $value(string) 送信された値
   * @parm $start(num) 開始年度
   * @parm $end(num) 終了年度
   * @return none;
   **/
  public function viewYear($value = "", $start = "", $end = ""){
    if($end == ""){ $end = date("Y"); }
    if($start >= 0 && $start <= $end ){
      $this->setYear($this->yearlist_num_arr, $start, $end);
    }
    $this->viewSelectOption($this->yearlist_num_arr,$value);
  }

  /**
   * 月のセレクトボックスを描画
   * @parm $value(string) 送信された値
   * @parm $start(num) 開始年度
   * @parm $end(num) 終了年度
   * @return none;
   **/
  public function viewMonth($value = "", $start = 1, $end = 12){
    if($start >= 0 && $start <= $end ){
      $this->setNumOption($this->monthlist_num_arr, $start, $end);
    }
    $this->viewSelectOption($this->monthlist_num_arr,$value);
  }

  /**
   * セレクトボックスのオプションを描画する
   * @parm $arr(array) 描画するリスト
   * @parm $value(string) 送信された値
   * @return none;
   **/
  public function viewSelectOption($arr,$value = ""){
    $output = "";
    foreach($arr as $key => $val){
      if($value == $key){
        $selected = 'selected="selected"'; $selectedflg = true;
      }
      $output .= '<option label="' . $val . '" value="' . $key . '" '. $selected . '>' . $val . '</option>'."\n";
      $selected = '';
    }
    if($selectedflg == true){
      $output = '<option value="">選択してください</option>'
              . $output;
    }else{
      $output = '<option value="" selected="selected">選択してください</option>'
              . $output;
    }
    echo $output;
  }

  
  /**
   * 渡された配列のキーと値で、Hiddenフィールドを描画する。
   * 多重の連想配列で渡す場合「$array[キー]["value"] = 値;」として定義していることが前提。
   * @param $arr array
   */
  public function viewHiddenField($arr){
    //modeとconfirm以外のフィールドをHiddenフィールドで描画する。
    //連想配列の場合
    if(isset($arr)){
      $output = "";
      foreach($arr as $key => $val){
        if(in_array($key, array("mode","confirm")) ){ continue;}
        if(is_array($val)){
          $output .= '<input type="hidden" name="'.$key.'" value="'.$val["value"].'" />'."\n";
        }elseif(is_string($val)){
          $output .= '<input type="hidden" name="'.$key.'" value="'.$val.'" />'."\n";
        }
      }
      echo $output;
    }
  }
  
  
  /**
   * 配列か連想配列かどうかを判別する。
   * 渡された配列のキーが0からの連番になっているかどうかで判別させる
   * @return boolean ;
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
   * 必須項目が入力されているかどうかチェックする。
   * @param &$info_arr array 入力されたデータ一式
   * @param $chklist_arr array チェックするキーをまとめた配列
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
   * @param &$info_arr array 入力されたデータ一式
   * @param $chklist_arr array チェックするキーをまとめた配列
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
   * @param &$info_arr array 入力されたデータ一式
   * @param $chklist_arr array チェックするキーをまとめた配列
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

} //end class cViewForm

