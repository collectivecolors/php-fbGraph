<?php

fb_graph_require('LogBase', 'log', TRUE);
fb_graph_require('LogEntry', 'log', TRUE);

/**
 * File based dumping logger.
 */
class FileDumper extends LogBase {

  /**
   * Constructor.
   *
   * Initialize class variables.
   */
  public function __construct($id, $types = array(), $log_path = '') {
    parent::__construct($id, $types);
    $this->setLogPath($log_path);
  }

  /**
   * Destructor.
   */
  public function __destruct() {
    // Close all open file handles.
    foreach ($this->files as $name => $handle) {
      fclose($handle);
    }
  }

  /*****************************************************************************
   * Properties
   */

  /**
   * Path name where the log file or files will be created.
   */
  protected $log_path = '';

  /**
   * Get the log path name.
   */
  public function getLogPath() {
    return $this->log_path;
  }

  /**
   * Set the log path name.
   */
  public function setLogPath($log_path) {
    $this->log_path = rtrim($log_path, '/');
  }

  //----------------------------------------------------------------------------

  /**
   * Log file name.
   *
   * If this is not set, different log files will be created for each type of
   * event encountered.
   *
   * Set this to something if you want a sequential dump of all events.
   */
  protected $log_name;

  /**
   * Get the log file name.
   */
  public function getLogName() {
    return $this->log_name;
  }

  /**
   * Set the log file name.
   */
  public function setLogName($name) {
    $this->log_name = $name;
  }

  //----------------------------------------------------------------------------

  /**
   * Holds a temporary reference to the type of log event received.
   *
   * This is used to retrieve the file handle associated with an event.
   */
  protected $log_entry_type = 'event';

  /**
   * Set log entry type.
   */
  protected function setLogEntryType($type) {
    $this->log_entry_type = $type;
  }

  //----------------------------------------------------------------------------

  /**
   * Whether or not to dump details about the log entry.
   *
   * These include:
   *  > log entry type
   *  > time of creation
   *  > file where event triggered
   *  > function or method where event triggered
   *  > line number where event triggered
   */
  protected $entryDetails = TRUE;

  /**
   * Set dump entry details flag.
   */
  public function dumpEntryDetails($bool = TRUE) {
    $this->dumpEntryDetails = $bool;
  }

  //----------------------------------------------------------------------------

  /**
   * Map of all active file handles.
   *
   * If LogName is set there will be only one.  If it is not set, then we will
   * have a file handle open for every log type we experience.
   */
  protected $files = array();

  /**
   * Get file handle that corresponds to a log event.
   */
  protected function getFile() {
    $name = ($this->getLogName() ? $this->getLogName() : $this->log_entry_type);
     
    if (is_null($files[$name])) {
      $files[$name] = fopen($this->getLogPath() . "/$name.log", 'a');
    }
    return $files[$name];
  }

  /*****************************************************************************
   * Utilities.
   */

  /**
   * Write strings to a log file.
   */
  protected function write() {
    $args = func_get_args();

    $file = $this->getFile();
    if (!$file) {
      return;
    }
    foreach ($args as $str) {
      fwrite($file, "$str\n");
    }
  }

  //----------------------------------------------------------------------------

  /**
   * Overrides: parent::logEntry().
   */
  protected function logEntry(LogEntry $entry) {
    $this->setLogEntryType($entry->getType());

    if ($this->entryDetails) {
      $this->dumpDetails($entry);
    }
    $this->dumpData($entry->getData());
  }

  //----------------------------------------------------------------------------

  /**
   * Dump log entry information to a specified file.
   */
  protected function dumpDetails(LogEntry $entry) {
    $this->write(
    $entry->getType()
    . ' ( ' . date('r', $entry->getTime()) . ' ) '
    . '   ' . $entry->getFile()
    . ' - ' . $entry->getFunction()
    . ' [ ' . $entry->getLine() . ' ]'
    );
  }

  //----------------------------------------------------------------------------

  /**
   * Dump data object to a specified file.
   */
  protected function dumpData($data) {
    // Null value.
    if (is_null($data)) {
      $this->write('NULL');
    }
    // Boolean.
    elseif (is_bool($data)) {
      $this->write(($data ? 'TRUE' : 'FALSE'));
    }
    // Number or string.
    elseif (is_string($data) || is_numeric($data)) {
      $this->write($data);
    }
    // [ Sequential or associative array ] or [ standard class or custom class ].
    elseif (is_array($data) || is_object($data)) {
      $this->write(print_r($data, TRUE));
    }
    // Unknown.
    else {
      $this->write("Unknown object [ $data ]");
    }
  }
}