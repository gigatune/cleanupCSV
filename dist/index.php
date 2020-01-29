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
      出力ファイルは、以下の形式のCSVファイルです。
      <ul>
        <li> 文字コードはBOM付きUTF-8 </li>
        <li> 改行コードはLF(Linuxサーバー上で実行した場合)</li>
        <li> カンマ区切り</li>
      </ul>
    </div>

    <div>
      以下のタグは強制的に改行コードに変換されます(擬似的なブロック表記)
      <ul>
        <li>&lt;br&gt;</li>
        <li>&lt;/p&gt;</li>
        <li>&lt;/h1&gt;</li>
        <li>&lt;/h2&gt;</li>
        <li>&lt;/h3&gt;</li>
        <li>&lt;/h4&gt;</li>
        <li>&lt;/h5&gt;</li>
        <li>&lt;/h6&gt;</li>
        <li>&lt;/center&gt;</li>
        <li>&lt;/div&gt;</li>
        <li>&lt;/li&gt;</li>
      </ul>
    </div>

  </body>
</html>
