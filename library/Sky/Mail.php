<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Sky_Mail {
    
    protected $_options;
    protected $_transport;
    protected $_mail;
    protected $_content = null;

    public function __construct($configs = null) {
        
        if(is_null($configs)) {
            $configs = Sky_Module_Config::getTable('mail');
        } else {
            $configs_sys = Sky_Module_Config::getTable('mail');
            $configs = array_merge($configs_sys,$configs); 
        }


        if ($configs['mail_transport'] == 'smtp') {
            $options = array(
                'auth'=>'login',
                'username'=>$configs['mail_username'],
                'password'=>$configs['mail_password'],
                'ssl'=>$configs['mail_security'],
                'port'=>(int)$configs['mail_port']
            );
            
            $transport = new Zend_Mail_Transport_Smtp($configs['mail_host'], $options);
            
        } elseif ($options['transport'] == 'mail') {
            $transport = null;
            
        } 
        
        
        
        
        $mail = new Zend_Mail();
        $mail->setFrom($configs['mail_from'],  utf8_decode($configs['mail_name']));
        
        $this->_mail = $mail;
        $this->_options = $configs;
        $this->_transport = $transport;
    }
    
    public function setTemplate($post=null,$template=null){
        $configs = $this->_options;
        
        if(!is_null($template)) {
            $configs['mail_template'] = $template;
        }

        
        $tpl = new Sky_Mail_Template();
        $tpl->tpl($configs['mail_template']);
        
        if(!is_null($post)){
            $tpl->setPost($post);
        }
        
        $this->setContent($tpl->get());

        return $this;
    }
    
    public function setContent($content){
        $this->_content = $content;
        
        return $this;
    }

    public function send($subject,$content=null,$to=null){
        $configs = $this->_options;
        
        if(!is_null($to)){
            $configs['mail_to'] = $to;
        }
        
        if(!is_null($content)) {
            $this->setContent($content);
        }
        
        $mail = $this->_mail;
        
        $mail->setSubject(utf8_decode($subject));
        $mail->addTo($configs['mail_to']);
        $mail->setBodyHtml($this->_content,$configs['mail_charset']);

        if((bool)$configs['mail_history']){
            $history = Sky_Model_Factory::getInstance()->setModule('mail')->getHistory();
            $history->insert(array('from'=>$configs['mail_from'],
                                   'to'=>$configs['mail_to'],
                                   'subject'=>$subject,
                                   'message'=>$mail->getBodyHtml(true)));
        }
        
        if($mail->send($this->_transport)) {
            
            return true;
        }
        
        return false;
    }
}

