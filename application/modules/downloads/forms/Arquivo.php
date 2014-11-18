<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Downloads_Forms_Arquivo extends Sky_Form_Jquery_Form {


    public function init() {
        
        $this->addElementPrefixPath(
                        'Admin_Forms_Validate', 
                        APPLICATION_PATH . '/modules/admin/forms/Validate/',
                        'validate');
        
        $this->addElementPrefixPath(
                        'Sky_Form_Validate', 
                         LIBRARY_PATH . '/Sky/Form/Validate/',
                        'validate');

        
        $this->setAction('')
                ->setName('andamento-form')
                ->setMethod('post');

        $assunto = $this->createElement('text', 'nome', array('class' => 'required'))
                ->setLabel('Título do arquivo')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        
        $descricao = $this->createElement('textarea', 'descricao', array('class' => ''))
                ->setLabel('Descrição')
                //->setRequired(true)
                //->setDescription('Nome representativo que será exibido no sistema.')
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        
        
        $file = $this->createElement('file', 'arquivo', array())
                ->setLabel('<i class="fa fa-share-square-o"></i> ')
                ->setDestination(PUBLIC_PATH.DS.'uploads'.DS.'docs')
                ->addValidator('ExcludeExtension', false, array('php', 'exe', 'bin', 'src', 'py'))
                ->addValidator('Size', false, 100000000000)
                ->setMaxFileSize(100000000000);
        
        $submit = $this->createElement('button', 'responder', array('type' => 'submit',
            'class' => '',
            'role' => 'button',
            'aria-disabled' => 'false'))
                ->setLabel('<i class="fa fa-cloud-upload"></i> Enviar Arquivos')
                ->removeDecorator('HtmlTag')
                ->removeDecorator('Label');
        

        $this->addElements(array($assunto, $descricao, $file, $submit));
        $this->addDisplayGroup(array('nome','descricao','arquivo','responder'), 'DataArquivo');
            


    }
    
    
}