<?php

namespace CSVUtils;

use SplFileObject;

class Util{

    public static function cleanupFile( $filename = null, $stream = null  ){

        $utf8file = static::__convertToUTF8( $filename );

        $filetype = static::__getFileType( $utf8file );

        $file = new SplFileObject( $utf8file );

        if( $filetype == "TSV" ){
            $file->setCsvControl("\t");
        }

        $file->setFlags( SplFileObject::READ_CSV );

        $bom="\xEF\xBB\xBF";
        fputs( $stream, $bom );
        foreach ($file as $line) {
            $stripped = static::__stripTagForArray( $line );
            fputcsv( $stream, $stripped );
        }

        return true;
    }

    public static function __convertToUTF8( $filename = null ){

        $charcode = static::__getCharCode( $filename);
        if( $charcode == "UTF-8"){
            return $filename;
        }

        $utf8file = tempnam( sys_get_temp_dir(), "convertutf8" );

        exec("nkf -w $filename > $utf8file", $out, $ret );
        if( $ret == 127 ){
            throw new \Exception('nkfがインストールされていません');
        }
        return $utf8file;

    }

    public static function __richTextToPlain( $str ){
        return html_entity_decode( strip_tags( str_replace( ["<br>", "</p>", "</h1>", "</h2>", "</h3>", "</h4>", "</h5>", "</h6>", "</center>", "</div>", "</li>"], "\n", $str ) ) );
    }

    public static function __getCharCode( $filename = null ){
        return exec("nkf -g " . $filename );
    }

    public static function __getFileType( $filename = null ){
        $fp = fopen($filename, 'r');
        $head = fgets($fp);
        fclose( $fp );

        $count = mb_substr_count( $head, "\t");
        if( $count > 10 ){
            return "TSV";
        }
        return "CSV";
    }

    public static function __stripTagForArray( $ar ){
        $out = [];

        foreach( $ar as $v ){
            $out[] = static::__richTextToPlain( $v );
        }

        return $out;
    }

    public static function nkfIsAvailable(){

        exec("nkf -v", $out, $ret );
        if( $ret == 127 ){
            return false;
        }
        return true;
    }
}
