<?php

require_once dirname(__FILE__).'/../lib/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

function vd($value = 'hello') {
    $args = func_get_args();
    foreach ($args as &$arg) {
        if (is_object($arg) && method_exists($arg, 'toArray')) {
            $arg = $arg->toArray();
        }
    }
    return call_user_func_array('var_dump', $args);
}

function dive() {
    $args = func_get_args();
    die(call_user_func_array('vd', $args));
}

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    mb_internal_encoding('UTF-8');
      mb_regex_encoding('UTF-8');
    $this->enableAllPluginsExcept(array());
    umask(0);
  }
  
  public function configureDoctrine(Doctrine_Manager $manager) {
      $manager->setAttribute(Doctrine_Core::ATTR_TBLNAME_FORMAT, 'rk_%s');
      $manager->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
      $manager->setAttribute(Doctrine::ATTR_DEFAULT_COLUMN_OPTIONS, array('notnull' => true));
      $manager->setAttribute(Doctrine::ATTR_DEFAULT_TABLE_CHARSET, 'utf8');
      $manager->setAttribute(Doctrine::ATTR_DEFAULT_TABLE_COLLATE, 'utf8_general_ci');
      $manager->setAttribute(Doctrine::ATTR_USE_DQL_CALLBACKS, true);
      sfConfig::set('doctrine_model_builder_options', array(
          'baseClassName' => 'tfGuardedRecord'
      ));
  }
  
}
