<?php

class Admin_LayoutController extends Zend_Controller_Action {

    public function init() {
        /* print('<div style="position:absolute; font-size:8px; background:#FFF; padding: 5px; z-index: 999; bottom: 0px; right: 0">');
          $user = Zend_Auth::getInstance()->getIdentity();
          Zend_Debug::dump($user['info']);
          print('</div>'); */
    }

    public function boxUserAction() {
        $user = Zend_Auth::getInstance();

        $this->view->assign('user', $user->getIdentity());
    }

    public function menuSystemAction() {
        $resources = Sky_Model_Factory::getInstance()->setModule('admin')->getAcl();
        $config = Sky_Config::getConfig();

        $user = Zend_Auth::getInstance()->getIdentity();
        $acl = Admin_Services_Acl::getInstance();
        $cache_key = md5("{$user['role']['name']}_menu");

        if (($container = Sky_Cache::getInstance()->load($cache_key)) !== false && ((bool) $config->cache->caching)) {
            return Zend_Registry::set('Zend_Navigation', $container);
        }

        $modulos = array();
        $privilegios = array();

        $rows = $resources->getResourcesByNav('module');
        foreach ($rows as $mod) {
            if (!$acl->isUserOrRoleAllowed($user['user']['role_id'], $user['user']['user_id'], $mod['module_name'], $mod['controller_name'], 'index'))
                continue;

            $rows_in = $resources->getPrivileges(array('controller_name=(?)' => $mod['controller_name'], '(module_name=(?) AND is_visible=1)' => $mod['module_name']));

            if (is_null($rows_in))
                continue;

            foreach ($rows_in[$mod['module_name']][$mod['controller_name']] as $name => $description) {
                if (!$acl->isUserOrRoleAllowed($user['user']['role_id'], $user['user']['user_id'], $mod['module_name'], $mod['controller_name'], $name))
                    continue;

                $privilegios[] = array(
                    'label' => $description,
                    'title' => $description,
                    'module' => $mod['module_name'],
                    'controller' => $mod['controller_name'],
                    'action' => $name,
                    'resource' => $mod['module_name'] . ':' . $mod['controller_name']
                );
            }

            if (count($privilegios) == 0)
                continue;

            $modulos[] = array(
                'label' => $mod['description'],
                'title' => $mod['description'],
                'module' => $mod['module_name'],
                'controller' => $mod['controller_name'],
                'resource' => $mod['module_name'] . ':' . $mod['controller_name'],
                'order' => $mod['order'],
                'pages' => $privilegios
            );

            $privilegios = null;
        }






        $relatorios = array();
        $privilegios = array();

        $rows = $resources->getResourcesByNav('relatorio');
        foreach ($rows as $mod) {
            if (!$acl->isUserOrRoleAllowed($user['user']['role_id'], $user['user']['user_id'], $mod['module_name'], $mod['controller_name'], 'index'))
                continue;

            $rows_in = $resources->getPrivileges(array('controller_name=(?)' => $mod['controller_name'], '(module_name=(?) AND is_visible=1)' => $mod['module_name']));

            if (is_null($rows_in))
                continue;

            foreach ($rows_in[$mod['module_name']][$mod['controller_name']] as $name => $description) {
                if (!$acl->isUserOrRoleAllowed($user['user']['role_id'], $user['user']['user_id'], $mod['module_name'], $mod['controller_name'], $name))
                    continue;

                $privilegios[] = array(
                    'label' => $description,
                    'title' => $description,
                    'module' => $mod['module_name'],
                    'controller' => $mod['controller_name'],
                    'action' => $name,
                    'resource' => $mod['module_name'] . ':' . $mod['controller_name']
                );
            }

            if (count($privilegios) == 0)
                continue;

            $relatorios[] = array(
                'label' => $mod['description'],
                'title' => $mod['description'],
                'module' => $mod['module_name'],
                'controller' => $mod['controller_name'],
                'resource' => $mod['module_name'] . ':' . $mod['controller_name'],
                'order' => $mod['order'],
                'pages' => $privilegios
            );

            $privilegios = null;
        }






        $admins = array();
        $rows = $resources->getResourcesByNav('admin');
        foreach ($rows as $mod) {
            if (!$acl->isUserOrRoleAllowed($user['user']['role_id'], $user['user']['user_id'], $mod['module_name'], $mod['controller_name'], 'index'))
                continue;


            $rows_in = $resources->getPrivileges(array('controller_name=(?)' => $mod['controller_name'], '(module_name=(?) AND is_visible=1)' => $mod['module_name']));

            if (is_null($rows_in))
                continue;

            foreach ($rows_in[$mod['module_name']][$mod['controller_name']] as $name => $description) {
                if (!$acl->isUserOrRoleAllowed($user['user']['role_id'], $user['user']['user_id'], $mod['module_name'], $mod['controller_name'], $name))
                    continue;

                $privilegios[] = array(
                    'label' => $description,
                    'title' => $description,
                    'module' => $mod['module_name'],
                    'controller' => $mod['controller_name'],
                    'action' => $name,
                    'resource' => $mod['module_name'] . ':' . $mod['controller_name']
                );
            }


            if (count($privilegios) == 0)
                continue;

            $admins[] = array(
                'label' => $mod['description'],
                'title' => $mod['description'],
                'module' => $mod['module_name'],
                'controller' => $mod['controller_name'],
                'resource' => $mod['module_name'] . ':' . $mod['controller_name'],
                'order' => $mod['order'],
                'pages' => $privilegios
            );

            $privilegios = null;
        }


        $helpdesk = array();
        $rows = $resources->getResourcesByNav('helpdesk');
        foreach ($rows as $mod) {
            if (!$acl->isUserOrRoleAllowed($user['user']['role_id'], $user['user']['user_id'], $mod['module_name'], $mod['controller_name'], 'index'))
                continue;


            $rows_in = $resources->getPrivileges(array('controller_name=(?)' => $mod['controller_name'], '(module_name=(?) AND is_visible=1)' => $mod['module_name']));

            if (is_null($rows_in))
                continue;

            foreach ($rows_in[$mod['module_name']][$mod['controller_name']] as $name => $description) {
                if (!$acl->isUserOrRoleAllowed($user['user']['role_id'], $user['user']['user_id'], $mod['module_name'], $mod['controller_name'], $name))
                    continue;

                $privilegios[] = array(
                    'label' => $description,
                    'title' => $description,
                    'module' => $mod['module_name'],
                    'controller' => $mod['controller_name'],
                    'action' => $name,
                    'resource' => $mod['module_name'] . ':' . $mod['controller_name']
                );
            }


            if (count($privilegios) == 0)
                continue;

            $helpdesk[] = array(
                'label' => $mod['description'],
                'title' => $mod['description'],
                'module' => $mod['module_name'],
                'controller' => $mod['controller_name'],
                'resource' => $mod['module_name'] . ':' . $mod['controller_name'],
                'order' => $mod['order'],
                'pages' => $privilegios
            );

            $privilegios = null;
        }


        $configs = array();
        $rows = $resources->getResourcesByNav('configs');
        foreach ($rows as $mod) {
            if (!$acl->isUserOrRoleAllowed($user['user']['role_id'], $user['user']['user_id'], $mod['module_name'], $mod['controller_name'], 'index'))
                continue;

            $rows_in = $resources->getPrivileges(array('controller_name=(?)' => $mod['controller_name'], 'module_name=(?)' => $mod['module_name']));
            foreach ($rows_in[$mod['module_name']][$mod['controller_name']] as $name => $description) {
                if (!$acl->isUserOrRoleAllowed($user['user']['role_id'], $user['user']['user_id'], $mod['module_name'], $mod['controller_name'], $name))
                    continue;

                $privilegios[] = array(
                    'label' => $description,
                    'title' => $description,
                    'module' => $mod['module_name'],
                    'controller' => $mod['controller_name'],
                    'action' => $name,
                    'resource' => $mod['module_name'] . ':' . $mod['controller_name']
                );
            }

            if (count($privilegios) == 0)
                continue;

            $configs[] = array(
                'label' => $mod['description'],
                'title' => $mod['description'],
                'module' => $mod['module_name'],
                'controller' => $mod['controller_name'],
                'resource' => $mod['module_name'] . ':' . $mod['controller_name'],
                'order' => $mod['order'],
                'pages' => $privilegios
            );
        }


        $sub = array();
        if ($acl->isUserOrRoleAllowed($user['user']['role_id'], $user['user']['user_id'], 'report', 'index', 'index')) {
            $sub[] =  array('label' => 'Portal',
                        'title' => 'Portal',
                        'module' => 'report',
                        'controller' => 'index',
                        'action'=>'index',
                        'resource'=>'report:index');
        }
        if ($acl->isUserOrRoleAllowed($user['user']['role_id'], $user['user']['user_id'], 'report', 'index', 'admin')) {
            $sub[] =  array('label' => 'Admin',
                        'title' => 'Admin',
                        'module' => 'report',
                        'controller' => 'index',
                        'action'=>'admin',
                        'resource'=>'report:admin');
        }
        

        $menu = array(
            'module' => array(
                'label' => 'Online Reports',
                'title' => 'Online Reports',
                'resource' => 'report:index',
                'module' => 'report',
                'order' => 0,
                'pages' => $sub
            ),
            'relatorio' => array(
                'label' => 'Downloads',
                'title' => 'Downloads',
                'module' => 'downloads',
                'controller' => 'index'
            ),
            'helpdesk' => array(
                'label' => 'Support',
                'title' => 'Support',
                'module' => 'helpdesk',
                'controller' => 'ticket',
                'action' => 'index'
            )
        );


        if ($user['role']['name'] == 'administrador' || $user['role']['name'] == 'asm') {
            $menu = array(
                'module' => array(
                    'label' => 'Gerenciamento',
                    'title' => 'Gerenciamento',
                    'uri' => '#',
                    'pages' => $modulos
                ),
                'helpdesk' => array(
                    'label' => 'HelpDesk',
                    'title' => 'HelpDesk',
                    'uri' => '#',
                    'pages' => $helpdesk
                ),
                'relatorio' => array(
                    'label' => 'Relatórios',
                    'title' => 'Relatórios',
                    'uri' => '#',
                    'pages' => $relatorios
                ),
                'admin' => array(
                    'label' => 'Administrativo',
                    'title' => 'Administrativo',
                    'uri' => '#',
                    'pages' => $admins
                )
            );
        }
        /*
          $menu = array(
          'module' => array(
          'label' => 'Online Reports',
          'title' => 'Online Reports',
          'module' => 'reports',
          'controller' => 'index',
          'resource' => 'index:index',
          ),
          'relatorio' => array(
          'label' => 'Relatórios',
          'title' => 'Relatórios',
          'uri' => '#',
          'pages' => $relatorios
          ),
          'helpdesk' => array(
          'label' => 'Support',
          'title' => 'Support',
          'uri' => '#',
          'pages' => $helpdesk
          ),
          'admin' => array(
          'label' => 'Administrativo',
          'title' => 'Administrativo',
          'uri' => '#',
          'pages' => $admins
          )
          );
         * */


        /*

         */

        $container = new Zend_Navigation($menu);


        if ((bool) $config->cache->caching)
            Sky_Cache::getInstance()->save($container, $cache_key, array('menu', $user['user']['username'], $user['role']['name']));

        Zend_Registry::set('Zend_Navigation', $container);
    }

}
