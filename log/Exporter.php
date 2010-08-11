<?php

fb_graph_require('LogBase', 'log', TRUE);
fb_graph_require('LogEntry', 'log', TRUE);

/**
 * Data export logger.
 */
class Exporter extends LogBase {

  /*****************************************************************************
   * Exporter properties.
   */
	
	/**
	 * Sequentially stored log entries.
	 */
	protected $log = array();
	
	/**
	 * Get all log entries so far (since the beginning or the last clear).
	 */
	public function getLog() {
		return $this->log;
	}
	
	/**
	 * Get all log messages.
	 * 
	 * We can either return the whole LogEntry object or just the string message.
	 */
	public function getMessages($return_entry = FALSE) {
		$results = array();
		foreach ($this->log as $entry) {
			if ($entry->isMessage()) {
				$results[] = ($return_entry ? $entry : $entry->getData());
			}
		}
		return $results;
	}
	
	/**
	 * Get all data objects logged.
	 * 
	 * We can either return the whole LogEntry object or just the data object.
	 */
	public function getData($return_entry = FALSE) {
    $results = array();
    foreach ($this->log as $entry) {
      if (!$entry->isMessage()) {
        $results[] = ($return_entry ? $entry : $entry->getData());
      }
    }
    return $results;	
	}
	
	/**
	 * Clear all existing log entries.
	 */
	public function clearLog() {
		$this->log = array();
	}
   
  /*****************************************************************************
   * Exporter utilities.
   */
  
  /**
   * Overrides: parent::logEntry().
   */
  protected function logEntry(LogEntry $entry) {
  	$this->log[] = $entry;      
  }
}
