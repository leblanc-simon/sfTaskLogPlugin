<?php
/**
 * Task base class for doctrine integration with log system
 * 
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD
 * @package     sfTaskLogPlugin
 * @version     1.0.0
 */
abstract class sfTaskLogDoctrine extends sfDoctrineBaseTask
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
}