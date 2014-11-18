<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Sky_Backup_Complete extends Sky_Backup {


    public function save(){
        $folder = TEMP_PATH . DS . $this->_folder . DS . Sky_Backup::F_COMPLETE;
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
        $file = $folder . DS . time() . '.sql';
        exec(sprintf('mysqldump --host=%s --user=%s --password=%s %s --quick --lock-tables --add-drop-table | gzip > %s', $host, $user, $password, $dbName, $file),$out,$parms);
    
    }
    
    public function lists(){
        $folder = TEMP_PATH . DS . $this->_folder . DS . Sky_Backup::F_COMPLETE;
        
        $files = array();
        
        foreach (array_reverse(scandir($folder)) as $f){
            if($f=='.'||$f=='..')
                continue;
            
            $files[] = array('name'=>$f,'info'=>lstat($folder.'/'.$f),'folder'=>$folder);
        }
        
        return $files;
    }
}
