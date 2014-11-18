<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin_Services_FileIntegrity {

    protected $_files = array();
    protected $_ext = array('php','phtml','cgi','html','htm','zip','rar','tar','exe','js','css','class','ini','htaccess');
    protected $_skip = array('.DS_Store', '._.DS_Store');
    protected $_integrity;
    protected $_diffs;
    protected $_dir;

    public function __construct() {
        $this->_integrity = Sky_Model_Factory::getInstance()->setModule('admin')->getFileIntegrity();
        $this->_dir = ROOT_PATH;
        $this->_skip[] = str_replace(array(ROOT_PATH, DS), '', TEMP_PATH);
        $this->_skip[] = str_replace(array(ROOT_PATH, DS), '', TEMP_PATH) . DS . 'cache';
        $this->_skip[] = str_replace(array(ROOT_PATH, DS), '', TEMP_PATH) . DS . 'backup';
        $this->_skip[] = str_replace(array(ROOT_PATH, DS), '', TEMP_PATH) . DS . 'backup' . DS . 'table';
        $this->_skip[] = TEMP_PATH;
    }

    public function getProfile() {

        $dir = new RecursiveDirectoryIterator($this->_dir);
        $iter = new RecursiveIteratorIterator($dir);
        while ($iter->valid()) {
            // skip unwanted directories
            if (!$iter->isDot() && !in_array($iter->getSubPath(), $this->_skip)) {
                // get specific file extensions
                if (!empty($this->_ext)) {
                    // PHP 5.3.4: if (in_array($iter->getExtension(), $ext)) {
                    if (in_array(pathinfo($iter->key(), PATHINFO_EXTENSION), $this->_ext)) {
                        $this->_files[$iter->key()] = hash_file("sha1", $iter->key());
                    }
                } else {
                    // ignore file extensions
                    $this->_files[$iter->key()] = hash_file("sha1", $iter->key());
                }
            }
            $iter->next();
        }
    }

    public function buildProfile() {
        $dir = new RecursiveDirectoryIterator($this->_dir);
        $iter = new RecursiveIteratorIterator($dir);
        while ($iter->valid()) {
            // skip unwanted directories
            if (!$iter->isDot() && !in_array($iter->getSubPath(), $this->_skip)) {
                // get specific file extensions
                if (!empty($this->_ext)) {
                    // PHP 5.3.4: if (in_array($iter->getExtension(), $ext)) {
                    if (in_array(pathinfo($iter->key(), PATHINFO_EXTENSION), $this->_ext)) {
                        $this->_files[$iter->key()] = hash_file("sha1", $iter->key());
                    }
                } else {
                    // ignore file extensions
                    $this->_files[$iter->key()] = hash_file("sha1", $iter->key());
                }
            }
            $iter->next();
        }

        $this->_integrity->insert($this->_files);
    }

    public function noSpecificCheckDiscrepancies() {
        $this->getProfile();

        $profile = $this->_integrity->getAll();
        if (count($profile) > 0) {
            $tmp = array();
            foreach ($profile as $value) {
                $tmp[$value["file_path"]] = $value["file_hash"];
            }
            $diffs = array_diff_assoc($this->_files, $tmp);
            //unset($tmp);

            $this->_diffs = $diffs;

            /* Zend_Debug::dump($diffs, 'DIFFS');
              Zend_Debug::dump($this->_files, 'FILES');
              Zend_Debug::dump($tmp, 'TEMP'); */
        }
        $this->_files = array();
        return $this;
    }

    public function specificCheckDiscrepancies() {
        $this->getProfile();

        $profile = $this->_integrity->getAll();
        if (count($profile) > 0) {
            $diffs = array();
            $tmp = array();
            foreach ($profile as $value) {
                if (!array_key_exists($value["file_path"], $this->_files)) {
                    $diffs["deleted"][$value["file_path"]] = $value["file_hash"];
                    $tmp[$value["file_path"]] = $value["file_hash"];
                } else {
                    if ($this->_files[$value["file_path"]] != $value["file_hash"]) {
                        $diffs["modified"][$value["file_path"]] = $this->_files[$value["file_path"]];
                        $tmp[$value["file_path"]] = $this->_files[$value["file_path"]];
                    } else {
                        // unchanged
                        $tmp[$value["file_path"]] = $value["file_hash"];
                    }
                }
            }
            if (count($tmp) < count($this->_files)) {
                $diffs["added"] = array_diff_assoc($this->_files, $tmp);
                $configs = Sky_Config::getConfig();
                
                if ((bool)$configs->fileIntegrity) {
                    foreach ($diffs["added"] as $key => $value) {
                        $handle = fopen($key, 'rw+');
                        fwrite($handle, "<?php die('Arquivo invalido'); ?>");
                        unset($handle);
                    }
                }
                
            }


            $this->_files = array();
            $this->_diffs = $diffs;
        }


        return $this;
    }

    public function getDiscrepancies() {
        $html = '';
        if (count($this->_diffs) > 0) {
            $html = "<h2>Foram encontrados erros de integridade na estrutura de arquivos:</h2>";
            $html .= "<table class=\"\" style=\"width: 100%;\">";
            foreach ($this->_diffs as $status => $affected) {
                if (is_array($affected) && !empty($affected)) {
                    $html .= "<tr>";
                    $html .= "<th>" . strtoupper($status) . "</th>";
                    $html .= "<td>
                                <table class=\"\" style=\"width: 100%;\">";
                    foreach ($affected as $path => $hash) {
                        $html .= "<tr><td>" . $path . "</td></tr>";
                    }
                    $html .= "</table>
                              </td>";
                    $html .= "</tr>";
                } else {
                    $html .= "<tr><td>" . $status . "</td></tr>";
                }
            }
            $html .= "</table>";
        } else {
            $html .= "<p>Estrutura de arquivos intacta.</p>";
        }

        $this->_files = array();
        return $html;
    }

}