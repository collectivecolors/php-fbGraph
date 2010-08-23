<?php

fb_graph_require('ICache', 'interface', TRUE);
fb_graph_require('CacheEntry', 'cache', TRUE);

/**
 * Static cache
 */
class StaticCache implements ICache {

  /**
   * Constructor.
   *
   * Initialize class variables.
   */
  public function __construct() {}

  /*****************************************************************************
   * Cache properties.
   */

  /**
   * Cache object or connection requests on a per instance level.
   *
   * Different apps might have different permissions so the data might be
   * different depending upon the app (and whether user is authenticated).
   */
  protected $cache = array();

  /**
   * Return an entry from the cache or NULL if nothing was found.
   */
  public function get($cache_id) {
    if ($this->cache[$cache_id] instanceof CacheEntry) {
      $cache_entry = $this->cache[$cache_id];

      if (!$cache_entry->isExpired()) {
        return $cache_entry->getData();
      }
      unset($this->cache[$cache_id]);
    }
    return NULL;
  }

  /**
   * Add an entry to the cache.
   *
   * If an object with the same cache id already exists, it is replaced.
   */
  public function add($cache_id, $data, $expire = NULL) {
    $this->cache[$cache_id] = new CacheEntry($cache_id, $data, $expire);
  }

  /**
   * Delete a cache entry or a group of entries.
   *
   * The first parameter of this function can be a Perl regexp that is evaluated
   * with preg_match().  If the first parameter is to be interpreted as a
   * regular expression, you must set the second parameter to TRUE.
   */
  public function delete($match, $is_pattern = FALSE) {
    if ($is_pattern) {
      foreach (array_keys($this->cache) as $cache_id) {
        if (preg_match($match, $cache_id)) {
          unset($this->cache[$cache_id]);
        }
      }
    }
    else {
      unset($this->cache[$match]);
    }
  }

  /**
   * Clear the entire cache.
   */
  public function clear() {
    $this->cache = array();
  }
}
