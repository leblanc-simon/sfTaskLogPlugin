<?php
/**
 * Task base class with log system
 * 
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD
 * @package     sfTaskLogPlugin
 * @version     1.0.0
 */
abstract class sfTaskLog extends sfBaseTask
{
  /**
   * Construct the object to log the events into a log system
   * @param   sfEventDispatcher   $dispatcher    The sfEventDispatcher object
   * @param   sfFormatter         $formatter     The sfFormatter object
   * @access  public
   * @since   1.0.0
   */
  public function __construct(sfEventDispatcher $dispatcher, sfFormatter $formatter)
  {
    parent::__construct($dispatcher, $formatter);
    
    sfTaskLog::includeClass();
    
    $logger = new sfTaskLogLogger($dispatcher, $this);
  }
  
  
  /**
   * Include the plugin class
   * @access  public
   * @static
   * @since   1.0.0
   */
  public static function includeClass()
  {
    $directory = sfConfig::get('sf_plugins_dir').DIRECTORY_SEPARATOR.'sfTaskLogPlugin'.DIRECTORY_SEPARATOR.'lib';
    $autoloader = sfSimpleAutoload::getInstance(); 
    $autoloader->addDirectory($directory); 
    $autoloader->addDirectory($directory.DIRECTORY_SEPARATOR.'adapter'); 
    $autoloader->register(); 
  }
}