<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Edi_File {

    protected $_configs;
    protected $_data;
    //protected $_estrutura = array('asm', "temp", "error", "xls");
    protected $_folder;
    protected $_edi;
    protected $_pack;
    protected $_empresa;
    protected $_ext = 'csv';

    const EDI_FILE_DEFAULT = "asm";

    public function __construct($module = 'varejo_ss') {
        $this->_configs = Sky_Module_Config::getTable($module);
        $this->_folder = new Edi_Folder();

        $this->_edi = Sky_Model_Factory::getInstance()->setModule('Varejo')->getEdi();
        $this->_pack = Sky_Model_Factory::getInstance()->setModule('Varejo')->getPack();
        $this->_empresa = Sky_Model_Factory::getInstance()->setModule('Varejo')->getEmpresa();
    }
    
    /**
     * Seta a extensão padrão dos arquivos de importação
     * @param string $ext
     * @return \Edi_File
     */
    public function setExt($ext) {
        $this->_ext = $ext;
        
        return $this;
    }

    /**
     * Gera arquivo PRD de um determinado CNPJ do período especificado
     * @param string $cnpj
     * @param string $start
     * @param string $end
     * @return boolean
     */
    public function create($cnpj, $start, $end) {
        $path = $this->getPathTemp() . DS . $cnpj;
        $csv = array();
        $packs = array();
        $date = new DateTime();

        $struc = $this->_folder->createTempStructure($path);
        
        if(count($struc)>0)
            $this->_data['create'][$cnpj] = $struc;


        if (is_null($cnpj)) {
            $this->_data['error'][] = "Nenhum CNPJ informado.";
        }
        
        $emp = $this->_empresa->getById($cnpj);

        if (count($emp) > 0) {
            foreach ($this->_edi->getByData($start, $cnpj) as $row) {
                if (!key_exists($row->epEmpresa_cnpj, $packs)) {
                    $packs[$row->epEmpresa_cnpj] = $this->_pack->setCnpj($row->epEmpresa_cnpj)->load();
                }

                $data = str_replace('-', '', $row->data);
                $csv[] = "{$row->nome};{$row->loja_cnpj};{$data};{$row->ean};{$packs[$row->epEmpresa_cnpj]->packToPn($row->pn)};{$row->vendas};{$row->estoque};{$row->familia};{$row->sitio};{$row->cidade};{$row->tipo_sitio};{$row->estado};{$date->format('Ymd')};{$date->format('Hi')}";
            }

            $normal = Sky_Text_Transform_Normalize::noSpaces($emp->nome);
            $name = "{$normal}_{$date->format('Ymd')}.{$this->_ext}";
            
        } else {
            $this->_data['error'][] = "O CNPJ {$cnpj} não está cadastrado no sistema.";
            
            return false;
        }
        
        if(count($csv)<=0) {
            return false;
        }
            
        $path .= DS . $name;    
        file_put_contents($path, implode("\015\012", $csv));

        $this->_data['path'] = $path;
        $this->_data['name'] = $name;
        
        return (bool)file_exists($path);
    }

    /**
     * Cria os arquivos PRD de todas as empresas
     * @param string $start
     * @param string $end
     */
    public function createAll($start, $end) {
        $empresas = $this->_empresa->getAll();
        foreach ($empresas as $emp) {
            if($this->create($emp['cnpj'], $start, $end)===TRUE){
                $this->_data['file'][$emp['cnpj']] = array('path'=>$this->_data['path'],'name'=>$this->_data['name']);
            } 
        }
    }

    /**
     * Retorna um array com todos os dados de LOG temporário
     * @return array
     */
    public function getData() {
        return $this->_data;
    }

    /**
     * Envia o arquivo PRD de determinada empresa
     * @param string $cnpj
     * @param string $ext
     * @return boolean
     */
    public function sendByCnpj($cnpj = null, $ext = 'csv') {
        $configs = $this->_configs;
        $path = $this->getPathTemp() . DS . $cnpj;

        if (!is_dir($path)) {
            return false;
        }

        $files = $this->listTempFiles($cnpj);

        if (count($files) > 0) {
            $Ftp = new Edi_File_Ftp();
            $this->_data['ftp']['connect'] = $Ftp->ftp($configs['varejo_ss_ftp_host'], $configs['varejo_ss_ftp_user'], $configs['varejo_ss_ftp_pass'], $configs['varejo_ss_ftp_folder']);

            foreach ($files as $file) {
                $this->_data['ftp']['send'][$cnpj][$file] = $Ftp->sendfile($file, $path . DS . $file, 2);
                $new = $path . DS . 'bkp' . DS . $file;

                copy($path . DS . $file, $new);
                //chmod($new, 0777);

                unlink($path . DS . $file);
            }
        }

        return true;
    }

    /**
     * Envia o arquivo PRD de todas as empresas
     * @return boolean
     */
    public function send() {
        $folderes = $this->_folder->listTempFolders();

        foreach ($folderes as $folder) {
            $this->sendByCnpj($folder);
        }

        return true;
    }

    /**
     * Retorna um array contendo a lista de arquivos PRD
     * @param string $cnpj
     * @return array
     */
    public function listTempFiles($cnpj) {
        $path = $this->getPathTemp() . DS . $cnpj;

        return $this->listFiles($path);
    }

    /**
     * Lista os arquivos de um determinado diretório
     * @param string $path
     * @return array
     */
    public function listFiles($path) {
        if (!is_dir($path)) {
            return array();
        }

        $scan = scandir($path);

        $files = array();
        foreach ($scan as $file) {
            if ($file[0] == '.')
                continue;

            if (!is_dir($path . DS . $file)) {
                $files[] = $file;
            }
        }

        return $files;
    }

    /**
     * Retorna o diretório padrão das pastas de arquivos PRD
     * @return string
     */
    public function getPathTemp() {
        return $this->_folder->getDefaultTempFolder();
    }
    
    /**
     * Retorna o diretório padrão dos arquivos de importação
     * @return type
     */
    public function getPath() {
        return $this->_folder->getDefaultFolder();
    }
    
    /**
     * Importa os arquivos de uma determinada empresa 
     * @param string $cnpj
     * @return boolean
     */
    public function importByCnpj($cnpj){
        $Configs = Sky_Model_Factory::getInstance()->setModule('Varejo')->getArquivoConfig();
        $Edi = Sky_Model_Factory::getInstance()->setModule('Varejo')->getEdi();
        $path = $this->_folder->getByCnpj($cnpj);

        if (!is_dir($path[$cnpj]['asm'])) {
            $this->_data['structure'] = $this->_folder->createStructure($path);
            //return false;
        }
        
        $config = $Configs->getByCnpj($cnpj);
        
        if(is_null($config)) {
            $this->_data['import'][$cnpj]['error'][] = "As configurações de importação para {$cnpj} não existem. Impossivel importar.";
            return false;
        }
        
        $files = $this->listFiles($path[$cnpj]['asm']);
        
        $Reader = new Edi_File_Reader();
        $Reader->factory($config['type'], $config->toArray());
        
        $id = array();
        
        foreach ($files as $file) {
            $sucess = true;
            $Reader->open($path[$cnpj]['asm'].DS.$file);

            foreach ($Reader->getFile() as $row) {
                $data = array_combine($Configs->getConlsInsertName(),$row);
                $id = $Edi->insert($data);
                if(!$id) {
                    $this->move($path[$cnpj]['asm'].DS.$file, $path[$cnpj]['error'].DS.$file);
                    $sucess = false;
                    $this->_data['import'][$cnpj][$file]['error'][] = "O Arquivo {$file} não pode ser importado, o arquivo contem erros que podem prejudicar a integridade dos dados. O arquivo foi movido para a pasta error.";
                    break;
                }
            }
            if($sucess){
                $this->_data['import'][$cnpj][$file]['status'] = "O Arquivo {$file} foi importado com sucesso.";
                $this->move($path[$cnpj]['asm'].DS.$file, $path[$cnpj]['temp'].DS.$file);
            }
        }
        
        return $this->_data;
    }
    
    /**
     * Importa todos os arquivos de todas as empresas
     * @return array
     */
    public function importAll(){
        $folder = $this->_folder->listFolders();
        foreach ($folder as $cnpj) {
            $this->importByCnpj($cnpj);
        }
        
        return $this->_data;
    }
    
    /**
     * Move um arquivo
     * @param string $from
     * @param string $to
     */
    public function move($from,$to) {
        if(copy($from, $to)){
            unlink($from);
        }
    }

}

/*
 $configs = $this->_configs;
        $path = ROOT_PATH . DS . $configs['varejo_ss_pasta_origem'] . DS . $cnpj;

        if (!is_dir($path)) {
            $this->_data['error'][] = "A pasta {$path} não foi encontrada no servidor. O sistema tentará cria-la.";
            if(!mkdir($path)) { 
                $this->_data['error'][] = "Não foi possivel criar a pasta.";
                return false;
            }
            
            if(!chmod($path, 0777)) {
                $this->_data['error'][] = "não foi possivel dar permissão a pasta {$path}";
            }
            
            foreach ($this->_estrutura as $folder) {
                mkdir($path.DS.$folder);
                chmod($path.DS.$folder, 0777);
                if($folder == Edi_File::EDI_FILE_DEFAULT) {
                    mkdir($path.DS.$folder.DS.$configs['varejo_ss_subpasta_origem']);
                    chmod($path.DS.$folder.DS.$configs['varejo_ss_subpasta_origem'], 0777);
                }
            }
            
        }

        $path .= DS . 'asm' . DS . $configs['varejo_ss_subpasta_origem'] . DS . "{$name}.{$ext}";

        $create = file_put_contents($path, implode("\015\012", $csv));

        if ($create) {
            $this->_data['path'] = $path;
            $this->_data['name'] = "{$name}.{$ext}";

            return true;
        }


        return false;
 */