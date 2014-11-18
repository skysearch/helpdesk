<?php

class Sky_Cache_File {
    /**
     * Support cache by URL or action
     */

    const CACHE_URL = 'url';
    const CACHE_WIDGET = 'widget';
    const CACHE_ACTION = 'action';
    const CACHE_FILE_PREFIX = 'cache_';

    public static function isCached($type, $key, $timeout) {
        $file = self::_generateFileName($type, $key);
        return self::isFileNewerThan($file, time() - $timeout);
    }

    public static function cache($type, $key, $content) {
        $file = self::_generateFileName($type, $key);
        $f = fopen($file, 'w');
        fwrite($f, $content);
        fclose($f);
    }

    public static function fromCache($type, $key) {
        $file = self::_generateFileName($type, $key);
        return file_get_contents($file);
    }

    /**
     * Generate file name
     *  
     * @param string $type
     * @param string $key
     * @return string
     */
    private static function _generateFileName($type, $key) {
        /**
         * TODO: Create file name by encoding the key
         */
        $dir = TEMP_PATH . DS . 'cache' . DS . $type;
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        return $dir . DS . self::CACHE_FILE_PREFIX . $type . '_' . $key . '.html';
    }

    /**
     * Compare modified time of file to given time stamp
     * 
     * @param string $file File name
     * @param string $compareTo Timestamp to compare
     * @return bool
     */
    public static function isFileNewerThan($file, $compareTo) {
        if (!file_exists($file)) {
            return false;
        }
        $modifiedDate = filemtime($file);
        if ($modifiedDate === false) {
            return false;
        }
        if ($modifiedDate < $compareTo) {
            return false;
        }
        return true;
    }

}