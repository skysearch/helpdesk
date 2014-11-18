<?php

class Mail_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        
    }

    public function indexAction()
    {

        //$mail = new Sky_Mail();
        //$mail->setTemplate(array('nome'=>'Renato David','email'=>'renato@skysearch.info'));
        //$mail->send('teste 101');
        
        /*$options = array(
                'auth'=>'login',
                'username'=>'renato@db9.com.br',
                'password'=>'d1a9r3a6',
                'ssl'=>'tls',
                'port'=>587
            );
            $transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $options);

        $mail = new Zend_Mail();
        $mail->setFrom('renato@db9.com.br','Renato David');
        $mail->setSubject('teste');
        $mail->addTo('renato@skysearch.info');
        $mail->setBodyHtml("<div></div>",'UTF-8');
        try {
            $mail->send($transport) ;
        } catch (Zend_Mail_Exception $e) {
            Zend_Debug::dump($e);
        }*/
    }

}

