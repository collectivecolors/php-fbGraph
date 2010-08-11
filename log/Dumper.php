<?php

fb_graph_require('LogBase', 'log', TRUE);
fb_graph_require('LogEntry', 'log', TRUE);

/**
 * Data dumping logger.
 */
class Dumper extends LogBase {
 
  /*****************************************************************************
   * Dumper utilities.
   */
  
  /**
   * Overrides: parent::logEntry().
   */
  protected function logEntry(LogEntry $entry) {
    $data = $entry->getData();
    
    print($entry->getType() . "\n");
    
  	if (is_string($data) || is_numeric($data)) {
      print($data);	
  	}
  	else {
      if (function_exists('krumo')) { // Optionally (and preferrably) use the krumo data dumper.
        krumo($data);
      }
      else {
        print_r($data);
      }	
  	}    
  }
}