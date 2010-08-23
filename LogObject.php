<?php

fb_graph_require('ILogger', 'interface', TRUE);

/**
 * Log enabled object base class.
 */
abstract class LogObject {

  /*****************************************************************************
   * LogObject properties.
   */

  /**
   * Alternate source of registered loggers to use.
   */
  protected $alt_log_object;

  /**
   * Use another LogObjects registered loggers for logging.
   */
  public function useLogObject(LogObject &$logger) {
    $this->alt_log_object = &$logger;
  }

  /**
   * Remove an alternate source of registered loggers.
   */
  public function clearLogObject() {
    $this->alt_log_object = NULL;
  }

  //----------------------------------------------------------------------------

  /**
   * Logging delegates.
   */
  protected $loggers = array();

  /**
   * Get all loggers
   */
  public function getLoggers() {
    return ($this->alt_log_object instanceof LogObject
    ? $this->alt_log_object->getLoggers()
    : $this->loggers
    );
  }

  /**
   * Set (or clear) logging delegates.
   */
  public function setLoggers() {
    $loggers = func_get_args();

    $this->loggers = array();
    foreach ($loggers as $logger) {
      if (!($logger instanceof ILogger)) {
        throw new Exception("$logger is not a valid ILogger class type.");
      }
      $this->loggers[$logger->getId()] = $logger;
    }
  }

  /**
   * Copy all loggers from another LogObject.
   */
  public function copyLoggers(LogObject $logger) {
    $this->loggers = $logger->getLoggers();
  }

  /**
   * Add a logging delegate to the list.
   */
  public function addLogger(ILogger $logger) {
    $this->loggers[$logger->getId()] = $logger;
  }

  /**
   * Delete a logging delegate.
   *
   * Parameter can either be a ILogger object or a string id of a logger.
   */
  public function deleteLogger($logger) {
    if ($logger instanceof ILogger) {
      unset($this->loggers[$logger->getId()]);
    }
    elseif (is_string($logger)) {
      unset($this->loggers[$logger]);
    }
  }

  /*****************************************************************************
   * Utilities.
   */

  /**
   * Set error messages.
   */
  public function _error() {
    $args = func_get_args();

    foreach ($this->getLoggers() as $id => $logger) {
      call_user_func_array(array($logger, 'error'), $args);
    }
  }

  //----------------------------------------------------------------------------

  /**
   * Set warnings.
   */
  public function _warning() {
    $args = func_get_args();

    foreach ($this->getLoggers() as $id => $logger) {
      call_user_func_array(array($logger, 'warning'), $args);
    }
  }

  //----------------------------------------------------------------------------

  /**
   * Set information.
   */
  public function _info() {
    $args = func_get_args();

    foreach ($this->getLoggers() as $id => $logger) {
      call_user_func_array(array($logger, 'info'), $args);
    }
  }

  //----------------------------------------------------------------------------

  /**
   * Set debug messages.
   */
  public function _debug() {
    $args = func_get_args();

    foreach ($this->getLoggers() as $id => $logger) {
      call_user_func_array(array($logger, 'debug'), $args);
    }
  }

  //----------------------------------------------------------------------------

  /**
   * Set forced debug messsages.
   *
   * These are meant to be displayed no matter what user settings have been
   * set.  This is meant for developers who need to spot debug without regard
   * to the end users preferences.
   */
  public function _fdebug() {
    $args = func_get_args();

    foreach ($this->getLoggers() as $id => $logger) {
      call_user_func_array(array($logger, 'fdebug'), $args);
    }
  }
}
