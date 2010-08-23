<?php

/**
 * Reusable cache entry class.
 */
class CacheEntry {

  /**
   * Constructor.
   *
   * Initialize class variables.
   */
  public function __construct($cache_id, $data, $expire = NULL) {
    $this->setId($cache_id);
    $this->setData($data);
    $this->setTime(time());
    $this->setExpiration($expire);
  }

  /*****************************************************************************
   * Cache properties.
   */

  /**
   * Unique cache id.
   */
  protected $id;

  /**
   * Get the current cache id.
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set current cache id.
   */
  public function setId($id) {
    $this->id = $id;
  }

  //----------------------------------------------------------------------------

  /**
   * Cached data.
   */
  protected $data;

  /**
   * Get current cached data.
   */
  public function getData() {
    return $this->data;
  }

  /**
   * Set current cached data.
   */
  public function setData($data) {
    $this->data = $data;
  }

  //----------------------------------------------------------------------------

  /**
   * Timestamp that this data was cached.
   */
  protected $time;

  /**
   * Get cache creation timestamp.
   */
  public function getTime() {
    return $this->time;
  }

  /**
   * Set cache creation timestamp.
   */
  public function setTime($time) {
    $this->time = $time;
  }

  //----------------------------------------------------------------------------

  /**
   * Timestamp that this data will expire.
   */
  protected $expiration;

  /**
   * Get cache expiration timestamp.
   */
  public function getExpiration() {
    return $this->expiration;
  }

  /**
   * Set cache expiration timestamp.
   */
  public function setExpiration($expiration) {
    $this->expiration = $expiration;
  }

  /**
   * Check if this cache entry has expired.
   *
   * If no expiration has been set, it never expires until deleted.
   */
  public function isExpired() {
    if (($expiration = $this->getExpiration()) && time() > $expiration) {
      return TRUE;
    }
    return FALSE;
  }
}