<?php
/**
 * The task log system which write the log into file
 * 
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD
 * @package     sfTaskLogPlugin
 * @version     1.0.0
 */
class sfTaskLogFile implements sfTaskLogAdapter
{
  private $sf_task_log_dir      = null;
  private $sf_task_log_filename = null;
  private $task_namespace       = null;
  private $task_name            = null;
  
  /**
   * Construct the object
   * @param   sfTask  $task   a sfTask object
   * @access  public
   * @since   1.0.0
   */
  public function __construct(sfTask $task)
  {
    $this->task_namespace = $task->getNamespace();
    $this->task_name      = $task->getName();
    
    $this->initLogFilename();
  }
  
  
  /**
   * Write the log into the file
   * @param   string    $str    The log to write into the file
   * @return  bool              True if it's OK, false else
   * @access  public
   * @since   1.0.0
   */
  public function writeLog($str)
  {
    $str = preg_replace(array('/>> .\[[0-9]{2};[0-9]m/', '/.\[[0-9]m/'), array('>> ', ''), $str);
    
    return (bool)file_put_contents($this->sf_task_log_filename, $str.PHP_EOL, FILE_APPEND);
  }
  
  
  /**
   * Initialize the log directory
   * @access  private
   * @static
   * @since   1.0.0
   */
  private function initLogDir()
  {
    // Directory is : data/sf_task_log/[TASK NAMESPACE]/[TASK NAME]
    $directory = sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'sf_task_log';
    $directory .= DIRECTORY_SEPARATOR.$this->task_namespace;
    $directory .= DIRECTORY_SEPARATOR.$this->task_name;
    
    if (file_exists($directory) === false || is_dir($directory) === false) {
      $filesystem = new sfFilesystem();
      if ($filesystem->mkdirs($directory) === false) {
        throw new sfFileException('Fail to create directory : '.$directory);
      }
    }
    
    $this->sf_task_log_dir = $directory;
  }
  
  
  /**
   * Initialize the log filename
   * @access  private
   * @static
   * @since   1.0.0
   */
  private function initLogFilename()
  {
    $this->initLogDir();
    $this->sf_task_log_filename = $this->sf_task_log_dir.DIRECTORY_SEPARATOR.date('YmdHis').'.log';
  }
}