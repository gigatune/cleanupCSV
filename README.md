# リッチテキストCSVデータをプレーンテキストCSVに変換するモジュール

## 必要条件

- nkf
- PHP 5.1以上

## 同梱のUIページを使って公開する

### public_html/ 以外にgitレポジトリをクローンできる、かつ、シンボリックリンクを張れるサーバー(推奨)

| 設定項目 | 例 |
|--|--|
| 公開するサーバー |  http://localhost/cleanup/ |
| 相当するディレクトリ | /public_html/cleanup |
| gitレポジトリをクローンするpath | /path/to/repo |

の場合、

```
$ git clone https://github.com/gigatune/cleanupCSV.git /path/to/repo/
$ ln -s /path/to/repo/dist /path/to/public_html/cleanup
```
を実行する。

bulma.js を使ったスタイリッシュなページを使いたければ、richUIブランチに切り替える

```
$ cd /path/to/repo
$ git fetch
$ git checkout -b richUI origin/richUI
```


#### 動作確認

1. http://localhost/cleanup/ にアクセス
2. [ファイル選択]ボタンを押し、レポジトリの中のtests/data 以下の適当なファイルを選択
3. [送信する]ボタンをクリック
4. cleanupされたCSVをダウンロードできる



### public_html/ 以下にしかアクセス権がない場合

レポジトリの中の
- src/Util.php
- dist/index.php
の2つのファイルを、公開ディレクトリの中に配置する。

index.phpを以下のように修正する。

```
-      require( '../src/Util.php');
+      require( './Util.php');
```

## 開発手順

```
# 必要なパッケージのインストール
$ composer.phar install
$ yarn install

# PHP-unit
$ ./vendor/bin/phpunit-watcher watch

# browsersync
$ ./browsersync.sh
```

browsersyncを使う場合は、上記の[同梱のUIページを使って公開する]の手順にしたがって、
 http://localhost:1080/cleanup で dist/index.php が実行されるように設定しておく


