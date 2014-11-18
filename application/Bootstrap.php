<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    /**
     * Register auto loader
     * 
     * @return void
     */
    protected function _initAutoload() {

        $autoloader = Zend_Loader_Autoloader::getInstance();
        $modules = Sky_Module_Loader::getInstance()->getModuleNames();

        foreach ($modules as $module) {
            new Sky_Autoloader(array(
                        'basePath' => APPLICATION_PATH . DS . 'modules' . DS . $module,
                        'namespace' => ucfirst($module) . '_',
                    ));
        }

        return $autoloader;
    }

    /**
     * Init session
     * 
     * @return void
     */
    protected function _initSession() {

        /**
         * Registry session handler 
         */
        Zend_Session::setSaveHandler(Admin_Services_SessionHandler::getInstance());

        /**
         * Allow user to set more session settings in application.ini
         * For example:
         * session.cookie_lifetime = "3600"
         * session.cookie_domain   = ".domain.ext"
         * @since 2.0.9
         */
        Zend_Session::setOptions(Sky_Config::getConfig()->resources->session->toArray());

        if (isset($_GET['PHPSESSID'])) {
            session_id($_GET['PHPSESSID']);
        } else if (isset($_POST['PHPSESSID'])) {
            session_id($_POST['PHPSESSID']);
        }

        Zend_Session::registerValidator(new Zend_Session_Validator_HttpUserAgent());
    }

    /**
     * Add action helpers
     * 
     * @since 2.0.7
     * @return void
     */
    protected function _initActionHelpers() {
        /**
         * Protect forms/pages from CSRF attacks
         */
        Zend_Controller_Action_HelperBroker::addHelper(new Sky_Controller_Action_Helper_Csrf());

        Zend_Controller_Action_HelperBroker::addPath(LIBRARY_PATH . DS . 'Sky' . DS . 'Controller' . DS . 'Action' . DS . 'Helper', 'Sky_Controller_Action_Helper');
    }
    
    
    public function _initViewHelpers() {
        
    }

    public function _initTranslate() {
        $configs = $this->getOptions();
        $options = $configs['resources'];

        $translate = new Zend_Translate('array', ROOT_PATH . DS . 'languages' . DS . $options['locale']['default'] . DS . 'Translation.php', $options['locale']['default']);
        Zend_Registry::set('Zend_Translate', $translate);
    }

    protected function _initPlugins() {
        $this->bootstrap('FrontController');
        $front = $this->getResource('FrontController');

        /**
         * Register plugins
         * The alternative way is that put plugin to /application/config/application.ini:
         * resources.frontController.plugins.pluginName = "Plugin_Class"
         */
        $front->registerPlugin(new Admin_Controllers_Plugin_Init())
                ->registerPlugin(new Admin_Controllers_Plugin_Auth())
                ->registerPlugin(new Admin_Controllers_Plugin_Log());


        $configs = Sky_Module_Config::getConfig('admin');
        if (!is_null($configs)) {
            $plugins = $configs->toArray();

            foreach ($plugins['plugins']['plugin'] as $plugin) {
                if (empty($plugin))
                    continue;
                $class = 'Admin_Controllers_Plugin_' . $plugin;
                $front->registerPlugin(new $class());
            }
        }

        /**
         * Error handler
         */
        $front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler(array(
                    'module' => 'admin',
                    'controller' => 'error',
                    'action' => 'error',
                )));
    }

    /*protected function _initRestRoute() {
        $this->bootstrap('frontController');
        //$configs = Sky_Module_Config::getConfig('default', 'rest')->toArray();
        $configs = Sky_Module_Config::getTable('api');
        $controllers = explode(',', $configs['api_controllers']);
        $frontController = Zend_Controller_Front::getInstance();
        
        $restRoute = new Zend_Rest_Route($frontController, array(), array('default' => $controllers));
        $frontController->getRouter()->addRoute('rest', $restRoute);
    }*/

}

