<?php

class Sky_Mail_Template {

    protected $_tpl = null;


    public function tpl($name = null) {

        $key = "{$name}_Template";
        $cache = Sky_Cache::getInstance();
        
        $config = Sky_Config::getConfig();
        $templates = Sky_Model_Factory::getInstance()->setModule('mail')->getTemplate();
        
        if(!(bool)$config->cache->caching) {
            $template = $templates->getByName($name);
            $this->_tpl = $template['content'];
            
            return $this;
        }
        
        if (!$result = $cache->load($key)) {
            $template = $templates->getByName($name);
            
            $cache->save($template['content'], $key);
            $this->_tpl = $template['content'];
        } 
    }

    function CampoReplace($campo, $replace, $code) {
        if (is_array($code)):
            $code = @implode("", $code);
        endif;

        if (is_array($replace)):
            $replace = @implode("", $replace);
        endif;

        if (!$busca = @ereg_replace($campo, $replace, $code))
            $busca = $code;

        return $busca;
    }

    function setPost($post) {
        if (!is_null($this->_tpl)){
            foreach ($post as $row => $valor){
                $this->_tpl = $this->CampoReplace('{{' . $row . '}}', $valor, $this->_tpl);
            }
        } else {
            return false;
        }
        
        return $this;
    }
    
    public function get(){
        return $this->_tpl;
    }

}

