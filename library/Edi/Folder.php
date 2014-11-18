<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Edi_Folder {

    protected $_configs;
    protected $_data;
    protected $_estrutura = array('asm', "temp", "error", "xls");

    const EDI_FILE_DEFAULT = "asm";
    const EDI_FILE_DEFAULT_BACKUP = "bkp";

    public function __construct($module = 'varejo_ss') {
        $this->_configs = Sky_Module_Config::getTable($module);
    }

    /*
     * Lista pastas nomeadas com o CNPJ
     * onde estão os arquivos para importação
     */

    public function listFolders() {

        $path = $this->getDefaultFolder();
        $scan = scandir($path);

        $folders = array();
        foreach ($scan as $folder) {
            if ($folder[0] == '.')
                continue;
            $folders[] = $folder;
        }

        return $folders;
    }

    /*
     * Lista pastas nomeadas com o CNPJ
     * onde estão os arquivos para envio 
     * por FTP
     */

    public function listTempFolders() {
        $path = $this->getDefaultTempFolder();
        $scan = scandir($path);

        $folders = array();
        foreach ($scan as $folder) {
            if ($folder[0] == '.')
                continue;
            $folders[] = $folder;
        }

        return $folders;
    }

    /**
     * Cria estrutura para gravação dos arquivos PRD's a serem exportados 
     * @param string $path
     * @return array
     */
    public function createTempStructure($path) {
        $data = array();

        if (!is_dir($path)) {
            $data['error'][] = "A pasta {$path} não foi encontrada no servidor. O sistema tentará cria-la.";

            if (!$this->_create($path)) {
                return $data['error'][] = "Não foi possivel criar a pasta.";
            }
            $data['sucess'][] = "A pasta {$path} foi criada com sucesso.";
        }

        $sub = $path . DS . self::EDI_FILE_DEFAULT_BACKUP;
        if (!is_dir($sub)) {
            if (!$this->_create($sub)) {
                return $data['error'][] = "Não foi possivel criar a subpasta {$sub}.";
            }

            $data['sucess'][] = "A pasta {$sub} foi criada com sucesso.";
        }

        return $data;
    }

    /**
     * Cria uma nova pasta no caminho indicado
     * @param string $path 
     */
    protected function _create($path) {
        if (!is_dir($path)) {
            return mkdir($path);
        }
    }

    /*
     * Retorna pasta padrão dos 
     * arquivos para importação
     */

    public function getDefaultFolder() {
        $configs = $this->_configs;

        return ROOT_PATH . DS . $configs['varejo_ss_pasta_origem'];
    }

    /**
     * Retorna o caminho padrão no servidor onde 
     * são gravados os arquivos PRD antes do envio
     */
    public function getDefaultTempFolder() {
        $configs = $this->_configs;

        return TEMP_PATH . DS . $configs['carejo_ss_local_folder'];
    }

    /**
     * Retorna os possiveis caminhos das pastas de importação do sistema
     * de uma determinada empresa
     * @param string $cnpj
     * @return array
     */
    public function getByCnpj($cnpj) {
        $configs = $this->_configs;
        $path = array();

        foreach ($this->_estrutura as $folder) {
            $path[$cnpj][$folder] = $this->getDefaultFolder() . DS . $cnpj . DS . $folder . DS . $configs['varejo_ss_subpasta_origem'];
        }

        return $path;
    }

    /**
     * Cria a estrutura de pastas de importação dos arquivos
     * @param array $path
     * @return array
     */
    public function createStructure($path) {
        $configs = $this->_configs;
        $data = array();
        foreach ($path as $cnpj=>$folders) {
            $default = $this->getDefaultFolder() . DS . $cnpj;

            if (!is_dir($default)) {
                $data['error'][] = "A pasta {$default} não foi encontrada no servidor. O sistema tentará cria-la.";

                if (!$this->_create($default)) {
                    return $data['error'][] = "Não foi possivel criar a pasta {$default}.";
                }
                $data['sucess'][] = "A pasta {$default} foi criada com sucesso.";

                
                foreach ($folders as $folder_in => $path_in) {
                    if (!$this->_create($default.DS.$folder_in)) {
                        $data['error'][] = "Não foi possivel criar a pasta {$default}.";
                        continue;
                    }
                    if (!is_dir($path_in)) {
                        if (!$this->_create($path_in)) {
                            return $data['error'][] = "Não foi possivel criar a subpasta {$path_in}.";
                        }

                        $data['sucess'][] = "A pasta {$path_in} foi criada com sucesso.";
                    }
                }
            }
        }
        
        return $data;
    }

}
