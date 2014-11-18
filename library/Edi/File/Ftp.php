<?php

/* * ****************************************** */
/* * *                FTP CLASS              ** */
/* * *           programed HEXIM 2004        ** */
/* * *              Czech republic           ** */
/* * ****************************************** */
/*
  CONSTRUCTOR INPUT:
  1. server name
  2. user name
  3. user password
  4. destination directory
 */

class Edi_File_Ftp {

    protected $ftp_server;
    protected $ftp_user_name;
    protected $ftp_user_pass;
    protected $dst_dir;
    protected $conn_id;
    protected $login_result;

    public function ftp($ftp_server, $ftp_user_name, $ftp_user_pass, $dst_dir) {
        if ($ftp_server != "" && $ftp_user_name != "" && $ftp_user_pass != "" && $dst_dir != "") {
            $this->ftp_server = $ftp_server;
            $this->ftp_user_name = $ftp_user_name;
            $this->ftp_user_pass = $ftp_user_pass;
            $this->dst_dir = $dst_dir;
        } else
            return false; // bad parametrs 
        if (!$this->connect() || !$this->setdir())
            return false; // bad connect or no exist directory
        else
            return true; // ALL OK
    }

    /* FTP connect */

    public function connect() {
        $this->conn_id = ftp_connect($this->ftp_server);
        $this->login_result = ftp_login($this->conn_id, $this->ftp_user_name, $this->ftp_user_pass);
        if ((!$this->conn_id) || (!$this->login_result))
            return false;
        else
            return true;
    }

    /* Set Directory */

    public function setdir() {
        if (!ftp_chdir($this->conn_id, $this->dst_dir))
            return false;
        else
            return true;
    }

    /* Send file */
    /*
      INPUT:
      $remote_file -> file for send
      $file        -> read file
      $mode        -> "FTP_BINARY","FTP_ASCII",...
     */

    public function sendfile($remote_file, $file, $mode = "FTP_BINARY") {
        if (ftp_put($this->conn_id, $remote_file, $file, $mode))
            return true;
        else
            return false;
    }

}
