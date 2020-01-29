<?php
require( '../src/Util.php');

$err_message = '';

if( \CSVUtils\Util::nkfIsAvailable() == false ){
  $err_message = "nkfがインストールされていません";
}

if( isset( $_FILES['orgfile'] ) ){

  $file = $_FILES['orgfile'];

  if( $file['error'] != 0 ){
    $err_message = 'ファイルのアップロードに失敗しました';

  }else{
    $uploaded = $file['tmp_name'];

    try{
      header("Content-Type: application/octet-stream");
      header("Content-Disposition: attachment; filename=converted.csv");

      $stream = fopen('php://output', 'w');
      \CSVUtils\Util::cleanupFile( $uploaded, $stream );
      return;
    }catch( Exception $e ){
      $err_message = $e->getMessage();
    }
  }
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>リッチテキストCSVクリーンアッププログラム</title>
    <link rel='stylesheet' type='text/css' href='main.css'>
  </head>
  <body>
    <h1>リッチテキストCSVクリーンアッププログラム</h1>

    <div STYLE='font-size: 120%; color:red;'>
      <?= $err_message ?>
    </div>

    <div class="mainform">
      <form action='./index.php' method='POST' enctype='multipart/form-data'>
        <input type='file' name='orgfile' />
        <br>
        <input type='submit' name='submit' value='送信する' />
      </form>
    </div>


    <div>
      リッチテキストのデータをフィールドに持つカンマ区切りのCSVファイルを、プレーンテキストのCSVファイルに変換します。
    </div>

    <div>
      変換元のファイルのサポートする文字コードは
      <ul>
        <li>UTF-16LE</li>
        <li>UTF-8</li>
      </ul>
    </div>

    <div>
      出力ファイルは、文字コードはBOM付きUTF-8、カンマ区切りのCSVファイルです。
    </div>

  </body>
</html>
