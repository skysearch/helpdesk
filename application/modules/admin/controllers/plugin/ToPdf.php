<?php

class Admin_Controllers_Plugin_ToPdf extends Zend_Controller_Plugin_Abstract 
{
    
    
    public function postDispatch(Zend_Controller_Request_Abstract $request) 
    {
        
        
        $params = $request->getParams();
        
        if(key_exists('out', $params)) {
            if(in_array('pdf',$params)){
                require_once LIBRARY_PATH .  DS . 'html2pdf' . DS . 'html2pdf.class.php';
                
                $moduleName = $request->getModuleName();
                $controllerName = $request->getControllerName();
                $actionName = $request->getActionName();
                
                Zend_Layout::startMvc()->setLayout('print');
                
                $configs = Sky_Module_Config::getConfig($moduleName,$controllerName);
                $print = $configs->print;

               
                $orientation = (key_exists('orientation', $params))?$params['orientation']:$print->orientation; 
                
                $html2pdf = new HTML2PDF(
                        $orientation,
                        $print->format,
                        $print->langue,
                        $print->unicode,
                        $print->encoding,
                        $print->margens->toArray());
                
                
                        
                $html2pdf->WriteHTML($this->getResponse()->getBody());
                $html2pdf->pdf->SetDisplayMode('fullpage');
                $html2pdf->Output('Imprimir ' . $controllerName . ' ' . $actionName . ' ' . date('dmY') .  '.pdf');

            }
        }
        
    }
    
}

