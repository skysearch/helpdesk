<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Sky_Backup_Table extends Sky_Backup {
    
    
    public function save() {
        $folder = TEMP_PATH . DS . $this->_folder . DS . Sky_Backup::F_TABLE . DS . date('dmY');
        if(!is_dir($folder)) {
            mkdir($folder);
            chmod($folder, 0777);
        }
        
        $this->_configs = Sky_Config::getConfig();
        
        $params = $this->_configs->resources->db->params;
        
        //print_r($params);
        
        $host = $params->host;
        $user = $params->username;
        $password = $params->password;
        $dbName = $params->dbname;
        
        $db = Sky_Db_Connection::getInstance()->connect();
        $tables = $db->fetchAll("SHOW tables");
        $fname = Sky_Backup::F_PART_NAME;

        foreach ($tables as $key => $value) {
            $table = $value->{$fname.$dbName};
            $file = $folder . DS . $dbName . '_' . $table . '.sql';        
            exec(sprintf('mysqldump --host=%s --user=%s --password=%s %s %s --quick --lock-tables --add-drop-table | gzip > %s', $host, $user, $password, $dbName,$table, $file));
        }
        

    }
    
    public function lists(){
        $folder = TEMP_PATH . DS . $this->_folder . DS . Sky_Backup::F_TABLE;
        $files = array();
        
        foreach (scandir($folder) as $f){
            $info = 0;
            $size = 0;
            
            if($f[0]=='.')
                continue;
            
            foreach (array_reverse(scandir($folder.DS.$f)) as $sf){
                $info = lstat($folder.DS.$f.DS.$sf);
                $size += $info['size']; 
            }
            
            $files[] = array('name'=>$f,'info'=>array('size'=>$size,'ctime'=>$info['ctime']),'folder'=>$folder);
        }
        
        return $files;
    }
}
