<?php
/**
 * The interface for log writer adapter
 * 
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD
 * @package     sfTaskLogPlugin
 * @version     1.0.0
 * @abstract
 */
interface sfTaskLogAdapter
{
  /**
   * Construct the object
   * @param   sfTask  $task   a sfTask object
   * @access  public
   * @since   1.0.0
   */
  public function __construct(sfTask $task);
  
  
  /**
   * Write the log
   * @param   string    $str    The log to write
   * @return  bool              True if it's OK, false else
   * @access  public
   * @since   1.0.0
   */
  public function writeLog($str);
}