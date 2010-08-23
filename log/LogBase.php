<?php

fb_graph_require('ILogger', 'interface', TRUE);
fb_graph_require('LogEntry', 'log', TRUE);

/**
 * Simple log.
 */
abstract class LogBase implements ILogger {

  /**
   * Constructor.
   *
   * Initialize class variables.
   */
  public function __construct($id, $types = array()) {
    $this->setId($id);
    $this->setLogTypes($types);
  }

  /*****************************************************************************
   * Simple log properties.
   */

  /**
   * Logger id
   */
  protected $id;

  /**
   * Get logger id.
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set logger id.
   */
  public function setId($id) {
    $this->id = $id;
  }

  //----------------------------------------------------------------------------

  /**
   * Log option choice constants.
   */
  const LOG_TYPE_NONE    = 'none';
  const LOG_TYPE_ALL     = 'all';
  const LOG_TYPE_ERROR   = 'error';
  const LOG_TYPE_WARNING = 'warning';
  const LOG_TYPE_INFO    = 'info';
  const LOG_TYPE_DEBUG   = 'debug';

  /**
   * Array of logging choices.
   */
  protected $log_options = array(
  self::LOG_TYPE_NONE => "Don't log anything",
  self::LOG_TYPE_ALL => 'Log all events.',
  self::LOG_TYPE_ERROR => 'Log all errors.',
  self::LOG_TYPE_WARNING => 'Log warning and error messages.',
  self::LOG_TYPE_INFO => 'Log data and information.',
  self::LOG_TYPE_DEBUG => 'Log debug related information.',
  );

  /**
   * Currently selected logging choices.
   */
  protected $log_types = array();

  /**
   * Set active logging choices.
   */
  public function setLogTypes($types = array()) {
    static $log_options;
    if (is_null($log_options)) {
      $log_options = array_keys($this->log_options);
    }
     
    foreach ($types as $type) {
      if (in_array($type, $log_options)) {
        switch ($type) {
          case self::LOG_TYPE_ALL:
            $this->log_types = $log_options;
            return;
             
          case self::LOG_TYPE_NONE:
            $this->log_types = array();
            return;
        }
        $this->log_types[$type] = TRUE; // Prevent duplicates.
      }
      else {
        throw new Exception("$type is not a supported log type.");
      }
    }
    $this->log_types = array_keys($this->log_types);
  }

  /**
   * Check if a specified type is enabled.
   */
  public function checkType($type) {
    switch ($type) {
      case self::LOG_TYPE_ALL:  return TRUE;
      case self::LOG_TYPE_NONE:	return FALSE;
    }
    return (in_array($type, $this->log_types));
  }

  //----------------------------------------------------------------------------

  /**
   * Log messages of specified type.
   */
  protected function log($type, $args) {
    if ($this->checkType($type)) {
      foreach ($args as $data) {
        $this->logEntry(new LogEntry($type, $data));
      }
    }
  }

  /**
   * Log a specific entry.
   *
   * This method must be overriden in subclasses!!
   */
  abstract protected function logEntry(LogEntry $entry);

  //----------------------------------------------------------------------------

  /**
   * Set error messages.
   */
  public function error() {
    $args = func_get_args();
    $this->log(self::LOG_TYPE_ERROR, $args);
  }

  /**
   * Set warning messages.
   */
  public function warning() {
    $args = func_get_args();
    $this->log(self::LOG_TYPE_WARNING, $args);
  }

  /**
   * Set information.
   */
  public function info() {
    $args = func_get_args();
    $this->log(self::LOG_TYPE_INFO, $args);
  }

  /**
   * Set debug messages.
   */
  public function debug() {
    $args = func_get_args();
    $this->log(self::LOG_TYPE_DEBUG, $args);
  }

  /**
   * Set forced debug messages.
   */
  public function fdebug() {
    $args = func_get_args();
    $this->log(self::LOG_TYPE_ALL, $args);
  }
}