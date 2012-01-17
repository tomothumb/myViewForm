<?php
require_once("./lib/myViewForm.php");
$mVF = new myViewForm();
?>

<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>demo</title>
<style type="text/css">
input{ padding: 5px; font-size:16px;}
</style>
</head>
<body>

<h1>【テスト】</h1>

<hr />

<form name="cartform" id="cartform" action="index.php" method="post" >

<table>
  <tr>
    <th>名前</th>
    <td><input type="text" name="name" value="TEST" /></td>
  </tr>
  <tr>
    <th>料金</th>
    <td><input type="text" name="price" value="1234" /></td>
  </tr>
  <tr>
    <th>メールアドレス</th>
    <td><input type="text" name="email" value="<?php echo $test_email; ?>" /></td>
  </tr>
  <tr>
    <th>有効期限</th>
    <td><select name="year" id="year" class="<?php echo $info_arr["year"]["error_class"];?>">
<?php $mVF->viewYear( 12 ); ?>
</select>
年　　
<select name="year" id="year" class="<?php echo $info_arr["year"]["error_class"];?>">
<?php $mVF->viewMonth( 12 ); ?>
</select>
月
</td>
  </tr>
  <tr>
    <th>電話番号</th>
    <td><input type="text" name="tel" value="<?php echo $test_tel; ?>" /></td>
  </tr>
  <tr>
    <th>&nbsp;</th>
    <td><input type="submit" name="submit" value="送信" /></td>
  </tr>
</table>
</form>

</body>
</html>