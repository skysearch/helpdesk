<?php
class PYC_Mail_Send extends PYC_Mail_Template
{
    protected $_options;
    protected $_transport;
    public $to;
    
    public function __construct($mailOption = 'mail'){
        $register = Zend_Registry::getInstance();
        $options = $register->get('configs');
        
        
        $options = $options[$mailOption];
        $this->_options = $options;
        
        //configurando envio
        if($options['transport']=='smtp') {
        	$this->_transport =  new Zend_Mail_Transport_Smtp($options['smtp'], $options['params']);
        } else {
        	$this->_transport = null;
        } 	
        
        parent::__construct();
    }
    
    
    public function mailCache($post, $to, $from = null,$reply=null) {
    	$transport = $this->_transport;
        $from = (is_null($from))?array('email'=>$this->_options['params']['username'],'nome'=>$this->_options['params']['defaultName']):$from;
        $reply = (is_null($reply))?$from:$reply;
        
        Zend_Mail::setDefaultFrom($from['email'], $from['nome']);
        Zend_Mail::setDefaultReplyTo($reply['email'],$reply['nome']);
        
        $body = parent::post($post);
        
        $mail = new Zend_Mail($this->_options['charset']);
        $mail->setBodyHtml($body);
        $mail->addTo($to['email'], $to['nome']);
        $mail->setSubject($to['subject']);
        $mail->send($transport);

        
        Zend_Mail::clearDefaultFrom();
        Zend_Mail::clearDefaultReplyTo();
    }
    
    
    
    public function tpl($file){
    	parent::tpl($file);
    }
       
    
    
    public function sendMail($body,$from=null,$reply=null) {
        
        $transport = $this->_transport;
        $from = (is_null($from))?array('email'=>$this->_options['params']['username'],'nome'=>''):$from;
        $reply = (is_null($reply))?$from:$reply;
        
        Zend_Mail::setDefaultFrom($from['email'], $from['nome']);
        Zend_Mail::setDefaultReplyTo($reply['email'],$reply['nome']);
        
        foreach($this->to as $to) {
            $mail = new Zend_Mail($this->_options['charset']);
            $mail->setBodyHtml($body);
            $mail->addTo($to['to']['email'], $to['to']['name']);
            $mail->setSubject($to['subject']);
            $mail->send($transport);
        }
        
        Zend_Mail::clearDefaultFrom();
        Zend_Mail::clearDefaultReplyTo();
    }
    
    
    
    public function sendMailSingle($body, $to, $from = null,$reply=null) {
        
        $transport = $this->_transport;
        $from = (is_null($from))?array('email'=>$this->_options['params']['username'],'nome'=>$this->_options['params']['defaultName']):$from;
        $reply = (is_null($reply))?$from:$reply;
        
        Zend_Mail::setDefaultFrom($from['email'], $from['nome']);
        Zend_Mail::setDefaultReplyTo($reply['email'],$reply['nome']);
        
        
        $mail = new Zend_Mail($this->_options['charset']);
        $mail->setBodyHtml($body);
        $mail->addTo($to['email'], $to['name']);
        $mail->setSubject($to['subject']);
        $mail->send($transport);

        
        Zend_Mail::clearDefaultFrom();
        Zend_Mail::clearDefaultReplyTo();
    }
    
    
    
    
    public function addTo($to=null,$subject=null){
        $this->to[] = array('to'=>$to,'subject'=>$subject);
    }
}
?>