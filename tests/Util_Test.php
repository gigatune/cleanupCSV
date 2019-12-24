<?php
namespace CSVUtils;

use PHPUnit\Framework\TestCase;

class UtilTest extends TestCase
{
    public function filePathForTest( $name = null ){
        return ( 'tests/data/' .  $name );
    }

    public function test_divタグを除去できる(){
        $this->assertEquals( "a\n", Util::__richTextToPlain( "<div>a</div>" ) );
        $this->assertEquals( "bbbbb\n", Util::__richTextToPlain( "<div>bbbbb</div>" ) );
    }

    public function test_aタグを除去できる(){
        $this->assertEquals( 'just', Util::__richTextToPlain( "<a>just</a>" ) );
    }

    public function test_タグ除去時にBRが改行される(){
        $this->assertEquals( "a\n", Util::__richTextToPlain( "a<br>" ) );

    }

    public function test_リッチテキストのリストが適宜改行される(){
        $this->assertEquals( "hoge\nfoo\n", Util::__richTextToPlain( "<ul><li>hoge</li><li>foo</li></ul>" ) );
    }

    public function test_タグ除去時にpの閉じタグで改行される(){
        $this->assertEquals( "a\nka", Util::__richTextToPlain( "a</p>ka" ) );
    }

    public function test_タグ除去時にh1タグで改行される(){
        $this->assertEquals( "hoge\nfoo", Util::__richTextToPlain( "<h1>hoge</h1><span>foo</span>" ) );
    }

    public function test_htmlエンティティが変換される(){
        $this->assertEquals( "<bar>", Util::__richTextToPlain( "&lt;bar&gt;") );
    }

    public function test_配列データからタグを除去できる(){
        $ar = ["<span>aa</span>", "<div class='hoge'>jack</div>" ];

        $expected = ['aa',"jack\n"];

        $this->assertEquals( $expected, Util::__stripTagForArray( $ar ));
    }

    public function test_配列データからタグを除去できる_タグ無し(){
        $ar = ['ユーザ名', '質問 x' ];

        $expected = ['ユーザ名','質問 x'];

        $this->assertEquals( $expected, Util::__stripTagForArray( $ar ));
    }

    public function test_utf8ファイルの文字コードを判定できる(){
        $file = "bb9_answer_utf8.csv";
        $this->assertEquals( "UTF-8", Util::__getCharCode( $this->filePathForTest( $file ) ) );
    }

    public function test_utf16leファイルの文字コードを判定できる(){
        $file = "bb9_answer_utf16le.csv";
        $this->assertEquals( "UTF-16", Util::__getCharCode( $this->filePathForTest( $file ) ) );
    }

    public function test_CSVを判定できる(){
        $file = "bb9_answer_utf16le.csv";
        $this->assertEquals( "CSV", Util::__getFileType( $this->filePathForTest( $file ) ) );
    }

    public function test_TSVを判定できる(){
        $file = "bb9_answer.tsv";
        $this->assertEquals( "TSV", Util::__getFileType( $this->filePathForTest( $file ) ) );
    }

    public function test_UTF8の日本語を含むCSVファイルをcleanupできる(){

        $file = "bb9_answer_utf8.csv";
        $expected_file = "bb9_answer_stripped.csv";  //BOM付きUTF-8

        $expected = file_get_contents( $this->filePathForTest( $expected_file ) );

        $stream = fopen('php://output', 'w');
        ob_start();
        Util::cleanupFile( $this->filePathForTest( $file ), $stream );
        $stdout_buf = ob_get_clean();

        $this->assertEquals( $expected , $stdout_buf );
    }

    public function test_UTF16の日本語を含むCSVファイルをcleanupできる(){

        $file = "bb9_answer_utf16le.csv";
        $expected_file = "bb9_answer_stripped.csv";  //BOM付きUTF-8

        $this->assertEquals( "UTF-16", Util::__getCharCode( $this->filePathForTest( $file ) ) );

        $expected = file_get_contents( $this->filePathForTest( $expected_file ) );

        $stream = fopen('php://output', 'w');
        ob_start();
        Util::cleanupFile( $this->filePathForTest( $file ), $stream );
        $stdout_buf = ob_get_clean();

        $this->assertEquals( $expected , $stdout_buf );

    }

    public function test_UTF16の日本語を含むTSVファイルをcleanupできる(){

        $file = "bb9_answer.tsv";
        $expected_file = "bb9_answer_stripped.csv";  //BOM付きUTF-8

        $this->assertEquals( "UTF-16", Util::__getCharCode( $this->filePathForTest( $file ) ) );

        $expected = file_get_contents( $this->filePathForTest( $expected_file ) );

        $stream = fopen('php://output', 'w');
        ob_start();
        Util::cleanupFile( $this->filePathForTest( $file ), $stream );
        $stdout_buf = ob_get_clean();

        $this->assertEquals( $expected , $stdout_buf );

    }

}
