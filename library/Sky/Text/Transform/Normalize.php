<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Sky_Text_Transform_Normalize {
    /* static public function toUtf8($text){
      $encode = mb_detect_encoding($text);

      if(($encode == 'UTF8-BOM' || $encode == 'UTF-8'))
      return $text;

      print($encode);exit;

      return mb_convert_encoding($text, 'UTF-8');
      }

      static public function toLatim1($text){

      $encode = mb_detect_encoding($text);

      if(($encode == 'Windows-1252' || $encode == 'ISO-8859-1'))
      return $text;

      return mb_convert_encoding($text, 'Windows-1252');
      } */

    static public function noSpaces($text) {
        $text = Sky_Text_Transform_Slug::noDiacritics($text);
        $palavras = explode(' ', $text);
        
        $text = array_map("Sky_Text_Transform_Normalize::capitalize", $palavras);
        
        return implode('', $text);
    }

    static public function capitalize($string) {
        $string = strtoupper($string[0]).substr($string, 1);
        return $string;
    }
}
