<?php

$err_message = '';

if( isset( $_FILES['orgfile'] ) ){

  $file = $_FILES['orgfile'];

  if( $file['error'] != 0 ){
    $err_message = 'ファイルのアップロードに失敗しました';

  }else{
      $uploaded = $file['tmp_name'];

      require( '../src/Util.php');

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


    <form action='./index.php' method='POST' enctype='multipart/form-data'>
      <input type='file' name='orgfile' />
      <br>
      <input type='submit' name='submit' value='送信する' />
    </form>

  </body>
</html>
