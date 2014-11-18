<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Sky_Text_Transform_Crypt {
    
    static public function encrypt($message,$base64 = true,$key = null)
    {
      if (!$td = mcrypt_module_open('rijndael-256', '', 'ctr', ''))
        return false;

      if(is_null($key)) {
        $configs = Sky_Config::getConfig();
        $key = $configs->crypt->key;
      }
      
      $message = serialize($message);
      $iv = mcrypt_create_iv(32, MCRYPT_RAND);

      if (mcrypt_generic_init($td, $key, $iv) !== 0)
        return false;

      $message = mcrypt_generic($td, $message);
      $message = $iv . $message;
      $mac = self::generate_key($message, $key);
      $message .= $mac;
      mcrypt_generic_deinit($td);
      mcrypt_module_close($td);

      if ($base64) 
        $message = base64_encode($message);

      return $message;
    }

  static public function decrypt($message,$base64 = true,$key = null) 
    {
      if ($base64)
        $message = base64_decode($message);

      if (!$td = mcrypt_module_open('rijndael-256', '', 'ctr', ''))
        return false;
      
      if(is_null($key)) {
        $configs = Sky_Config::getConfig();
        $key = $configs->crypt->key;
      }

      $iv = substr($message, 0, 32);
      $mo = strlen($message) - 32;
      $em = substr($message, $mo);
      $message = substr($message, 32, strlen($message) - 64);
      $mac = self::generate_key($iv . $message, $key);

      if ($em !== $mac)
        return false;

      if (mcrypt_generic_init($td, $key, $iv) !== 0)
        return false;
       
      $message = mdecrypt_generic($td, $message);
      $message = unserialize($message);

      mcrypt_generic_deinit($td);
      mcrypt_module_close($td);

      return $message;
    }

  public function generate_key($p, $s, $c = 1000, $kl = 32, $a = 'sha256') 
    {
      $hl = strlen(hash($a, null, true));
      $kb = ceil($kl / $hl);
      $dk = '';

      for ($block = 1; $block <= $kb; $block++) 
        {
          $ib = $b = hash_hmac($a, $s . pack('N', $block), $p, true);

          for ($i = 1; $i < $c; $i++)
            $ib ^= ($b = hash_hmac($a, $b, $p, true));

          $dk .= $ib;
        }
      return substr($dk, 0, $kl);
    }

  public function random_salt($minlength, $maxlength) 
    {
      $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789~!@#$%^&*()_+`-={}|\\]?[\":;'><,./";

      mt_srand((double)microtime() * 1000000);

      if ($minlength > $maxlength)
        $length = mt_rand($maxlength, $minlength);
      else
        $length = mt_rand($minlength, $maxlength);

      for ($i = 0; $i < $length; $i++)
        $salt .= $charset[(mt_rand(0,(strlen($charset)-1)))];
      return $salt;
    }
    
}

