<?php

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
// defined('SITE_ROOT') ? null : define('SITE_ROOT', DS . '/home/healaegz/xedo.health-mental.com' . DS . 'back_xedo');
defined('SITE_ROOT') ? null : define('SITE_ROOT', DS . 'wamp64' . DS . 'www' . DS . 'backend');

defined('INC_PATH') ? null : define('INC_PATH', SITE_ROOT . DS . 'includes');
defined('CORE_PATH') ? null : define('CORE_PATH', SITE_ROOT . DS . 'core');

require_once(INC_PATH . DS . 'config.php');


require_once(CORE_PATH . DS . 'enseignant.php');
require_once(CORE_PATH . DS . 'apprenant.php');
require_once(CORE_PATH . DS . 'admin.php');
require_once(CORE_PATH . DS . 'universite.php');
require_once(CORE_PATH . DS . 'epreuve.php');
require_once(CORE_PATH . DS . 'telechargement.php');
require_once(CORE_PATH . DS . 'authentification.php');
/*require_once(CORE_PATH . DS . 'cartes.php');
require_once(CORE_PATH . DS . 'notifications.php');
require_once(CORE_PATH . DS . 'fcm_sender.php');
require_once(CORE_PATH . DS . 'transactions.php');*/
