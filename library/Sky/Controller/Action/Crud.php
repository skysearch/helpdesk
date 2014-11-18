<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'Zend' . DS . 'Controller' . DS . 'Action.php';

class Sky_Controller_Action_Crud extends Zend_Controller_Action {
    
    protected $_configs = null;
    protected $_url;
    protected $_idparam = 'id';
    protected $_class;
    protected $_columns;
    protected $_dataTableConfigs = null;
    

    protected function _getConfigs(){
        if(is_null($this->_configs)) {
            return $this->_configs = Sky_Config::getConfig();
        }
        
        return $this->_configs;
    }
    
    protected function _setIdParam($name){
        $this->_idparam = $name;
    }
    
    protected function _setUrl($url){
        $this->_url = $url;
    }
    
    protected function _setClass($class){
        $this->_class = $class;
    }
    
    protected function _setColumns($columns){
        $configs = $this->_getConfigs();
        
        $this->_columns = $columns;
        $this->_configs = $configs;
        
        
        
        $type = ((bool)$configs->data->list->json)?'json':'html';
        $this->view->assign('cols',$columns);
        $this->view->assign('type',$type);
        
    }
    
    protected function _setDataTableConfig($configs){
        if(count($this->_dataTableConfigs)>0) {
             $configs = array_merge_recursive($this->_dataTableConfigs,$configs);
        } 
        $this->_dataTableConfigs = $configs;
        $this->view->assign('conf',$configs);
    }

    protected function _getDataTableConfig(){
        return $this->_dataTableConfigs;
    }

    protected function _setMessages($messages){
        $this->_messages = $messages;
    }


    public function listarAction(){
        $configs = $this->_getConfigs();
        $list = null;
        $post = null;
        
        if((bool)$configs->data->list->json) {
            if($this->getRequest()->isPost()){
                $this->_helper->getHelper('viewRenderer')->setNoRender();
                $this->_helper->getHelper('layout')->disableLayout();

                $post = $this->getRequest()->getPost();

                if($this->getRequest()->getParam('format')=='json'){
                    $list = $this->_class->getToDataTable($post,$this->_columns,true);
                    print($list); exit;
                }
            }
        } else {
            $list = $this->_class->getToDataTable($post,$this->_columns,false);
        }
        
        $this->view->assign('list',$list);
        
    }
    
    public function apagarAction(){
        $id = $this->getRequest()->getParam($this->_idparam);
        $action = $this->getRequest()->getControllerName();
        if ($this->_class->delete($id)) {

            $this->_helper->getHelper('FlashMessenger')
                    ->addMessage("Registro apagado com sucesso.", 'sucess');
        } else {
            $this->_helper->getHelper('FlashMessenger')
                    ->addMessage("Não foi possível apagar o registro.", 'error');
        }

        $this->_helper->redirector->goToRoute($this->_url, null, true);
    }
}

