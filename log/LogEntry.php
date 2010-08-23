<?php

/**
 * Reusable log entry class.
 */
class LogEntry {

  /**
   * Constructor.
   *
   * Initialize class variables.
   */
  public function __construct($type, $data) {
    $caller = $this->getCaller();

    $this->setType($type);
    $this->setData($data);

    $this->setTime(time());

    $this->setFile($caller['file']);
    $this->setFunction($caller['function']);
    $this->setLine($caller['line']);
  }

  /*****************************************************************************
   * Log entry properties.
   */

  /**
   * Entry type (error | warning | info | debug | all).
   */
  protected $type;

  /**
   * Get the log entry type.
   */
  public function getType() {
    return $this->type;
  }

  /**
   * Set log entry type.
   */
  protected function setType($type) {
    $this->type = $type;
  }

  //----------------------------------------------------------------------------

  /**
   * Logged data.
   */
  protected $data;

  /**
   * Get logged data.
   */
  public function getData() {
    return $this->data;
  }

  /**
   * Set logged data.
   */
  protected function setData($data) {
    $this->data = $data;
  }

  /**
   * Check if this log entry is a message we can display.
   */
  public function isMessage() {
    return (is_string($this->data) && strlen($this->data));
  }

  //----------------------------------------------------------------------------

  /**
   * Timestamp that this entry was logged.
   */
  protected $time;

  /**
   * Get log entry creation timestamp.
   */
  public function getTime() {
    return $this->time;
  }

  /**
   * Set log entry creation timestamp.
   */
  protected function setTime($time) {
    $this->time = $time;
  }

  //----------------------------------------------------------------------------

  /**
   * File where this entry was created.
   */
  protected $file;

  /**
   * Get file that triggered event.
   */
  public function getFile() {
    return $this->file;
  }

  /**
   * Set file that triggered event.
   */
  protected function setFile($file) {
    $this->file = $file;
  }

  //----------------------------------------------------------------------------

  /**
   * Function or method where this entry was created.
   */
  protected $function;

  /**
   * Get function or method that triggered event.
   */
  public function getFunction() {
    return $this->function;
  }

  /**
   * Set function or method that triggered event.
   */
  protected function setFunction($function) {
    $this->function = $function;
  }

  //----------------------------------------------------------------------------

  /**
   * File line number where this entry was created.
   */
  protected $line;

  /**
   * Get file line number that triggered event.
   */
  public function getLine() {
    return $this->line;
  }

  /**
   * Set file line number that triggered event.
   */
  protected function setLine($line) {
    $this->line = $line;
  }

  /*****************************************************************************
   * Utilities
   */

  /**
   * Return information about the caller that triggered the event.
   */
  protected function getCaller() {
    $call_stack = debug_backtrace();
    foreach ($call_stack as $caller) {
      // Ignore calls from log classes.
      if (!preg_match('/log\/[^\/]+$/', $caller['file'])) {
        return $caller;
      }
    }
    return array();
  }
}