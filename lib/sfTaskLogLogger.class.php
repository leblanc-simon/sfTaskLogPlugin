<?php
/**
 * Class  for task with log system
 * 
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD
 * @package     sfTaskLogPlugin
 * @version     1.0.0
 */
class sfTaskLogLogger
{
  /**
   * @var     mixed     The log system object
   * @access  private
   * @since   1.0.0
   */
  private $log_object = null;
  
  /**
   * @var     sfTask    The sfTask object
   * @access  private
   * @since   1.0.0
   */
  private $task = null;
  
  
  /**
   * Construct the object to log the events into a log system
   * @param   sfEventDispatcher   $dispatcher    The sfEventDispatcher object
   * @param   sfTask              $formatter     The sfTask object
   * @access  public
   * @since   1.0.0
   */
  public function __construct(sfEventDispatcher $dispatcher, sfTask $task)
  {
    $this->task = $task;
    
    $class = sfConfig::get('app_sfTaskLog_class', 'sfTaskLogFile');
    $this->log_object = new $class($task);
    if (($this->log_object instanceof sfTaskLogAdapter) === false) {
      throw new sfException('The logger adapter must implement sfTaskLogAdapter');
    }
    
    $dispatcher->connect('command.pre_command', array($this, 'listenPreCommandEvent'));
    $dispatcher->connect('command.post_command', array($this, 'listenPostCommandEvent'));
    $dispatcher->connect('command.log', array($this, 'listenLogEvent'));
  }
  
  
  /**
   * Method who listen the command.pre_command event and write the log in the log system
   * @param   sfEvent   $event       The event call
   * @access  public
   * @since   1.0.0
   */
  public function listenPreCommandEvent(sfEvent $event)
  {
    $arguments  = '';
    $options    = '';
    
    // serialize the arguments
    foreach ($event['arguments'] as $key => $value) {
      if (empty($arguments) === false) {
        $arguments .= ', ';
      }
      $arguments .= $key.' => '.$value;
    }
    
    // serialize the options
    foreach ($event['options'] as $key => $value) {
      if (empty($arguments) === false) {
        $options .= ', ';
      }
      $options .= $key.' => '.$value;
    }
    
    $log = 'The command '.$this->task->getNamespace().'::'.$this->task->getName().' start at '.date('Y-m-d H:i:s').' with :'.PHP_EOL;
    $log .= ' - arguments : '.$arguments.PHP_EOL;
    $log .= ' - options   : '.$options.PHP_EOL;
    
    $this->log_object->writeLog($log);
  }
  
  
  /**
   * Method who listen the command.post_command event and write the log in the log system
   * @param   sfEvent   $event       The event call
   * @access  public
   * @since   1.0.0
   */
  public function listenPostCommandEvent(sfEvent $event)
  {
    $log = PHP_EOL.'The command '.$this->task->getNamespace().'::'.$this->task->getName().' end at '.date('Y-m-d H:i:s');
    $this->log_object->writeLog($log);
  }
  
  
  /**
   * Method who listen the command.log event and write the log in the log system
   * @param   sfEvent   $event       The event call
   * @access  public
   * @since   1.0.0
   */
  public function listenLogEvent(sfEvent $event)
  {
    $this->log_object->writeLog($event[0]);
  }
}