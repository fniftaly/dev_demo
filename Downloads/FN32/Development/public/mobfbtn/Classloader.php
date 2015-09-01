<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Classloader
 *
 * @author farad
 */
class My_Controller_Action_Helper_GetModel extends Zend_Controller_Action_Helper_Abstract
{
  /**
   * @var Zend_Loader_PluginLoader
   */
  protected $_loader;

  /**
   * Initialize plugin loader for models
   * 
   * @return void
   */
  public function __construct()
  {
    // Get all models across all modules
    $front = Zend_Controller_Front::getInstance();
    $curModule = $front->getRequest()->getModuleName();

    // Get all module names, move default and current module to
    //  back of the list so their models get precedence
    $modules = array_diff(
      array_keys($front->getDispatcher()->getControllerDirectory()),
      array('default', $curModule)
    );
    $modules[] = 'default';
    if ($curModule != 'default') {
      $modules[] = $curModule;
    }

    // Generate namespaces and paths for plugin loader
    $pluginPaths = array();
    foreach($modules as $module) {
      $pluginPaths[ucwords($module)] = $front->getModuleDirectory($module) . '/models';
    }

    // Load paths
    $this->_loader = new Zend_Loader_PluginLoader($pluginPaths);
  }

  /**
   * Load a model class and return an object instance
   * 
   * @param  string $model 
   * @return object
   */
  public function getModel($model)
  {
    $class = $this->_loader->load($model);
    return new $class;
  }

  /**
   * Proxy to getModel()
   * 
   * @param  string $model 
   * @return object
   */
  public function direct($model)
  {
    return $this->getModel($model);
  }
}

?>
