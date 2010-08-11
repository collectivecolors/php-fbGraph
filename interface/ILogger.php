<?php

/**
 * General logging interface.
 */
interface ILogger {
  
  /**
   * Get logger id.
   */
  public function getId();
  
  /**
   * Set error messages.
   */
  public function error();
  
  /**
   * Set warning messages.
   */
  public function warning();
  
  /**
   * Set information.
   */
  public function info();
  
  /**
   * Set debug messages.
   */
  public function debug();
  
  /**
   * Set forced debug messages.
   */
  public function fdebug();
}