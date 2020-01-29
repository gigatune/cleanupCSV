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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
    <link rel="stylesheet" href="./main.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
  </head>
  <body>
    <nav class="navbar" role="navigation" aria-label="main navigation">
      <div class="navbar-brand">
        <span class="navbar-item title is-5">リッチテキストCSVクリーンアップ</span>
      </div>
    </nav>

    <section>

      <div class="container">

        <?php if( $err_message != "" ): ?>
          <div class="notification is-danger">
            <?= $err_message ?>
          </div>
        <?php endif; ?>

        <div class="columns">
          <div class="column">
            <div class="card">

              <div class="card-content">
                <div>
                     リッチテキストのデータをフィールドに持つカンマ区切りのCSVファイルを、プレーンテキストのCSVファイルに変換します。
                </div>

                <div class="content" STYLE="margin-bottom: 0rem;">
                  変換元のファイルのサポートする文字コードは
                  <ul STYLE="margin-top:0em;">
                    <li> UTF-16LE</li>
                    <li> UTF-8</li>
                  </ul>
                </div>

                <div>
                     出力ファイルは、文字コードはBOM付きUTF-8、改行コードはLF(Linuxサーバー上で実行した場合)、カンマ区切りのCSVファイルです。
                </div>

              </div>
            </div>
          </div>
          <div class="column">
            <form action='./richui.php' method='POST' enctype='multipart/form-data'>

              <div id="file-js" class="file has-name">
                <label class="file-label">
                  <input class="file-input" type="file" name="orgfile">
                  <span class="file-cta">
                    <span class="file-icon">
                      <i class="fas fa-upload"></i>
                    </span>
                    <span class="file-label">
                      ファイルを選択してください
                    </span>
                  </span>
                  <span class="file-name">
                    ...
                  </span>
                </label>
              </div>
              <br>
              <input class="button is-primary" type='submit' name='submit' value='送信する' />
            </form>

          </div>
        </div>


      </div>
    </section>

    <script>
     const fileInput = document.querySelector('#file-js input[type=file]');
     fileInput.onchange = () => {
       if (fileInput.files.length > 0) {
         const fileName = document.querySelector('#file-js .file-name');
         fileName.textContent = fileInput.files[0].name;
       }
     }
    </script>
  </body>
</html>
