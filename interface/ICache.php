<?php

/**
 * General cache interface.
 */
interface ICache {
  
  /**
   * Return an entry from the cache or NULL if nothing was found.
   */
	public function get($request_id);
	
	/**
   * Add an entry to the cache.
   */
	public function add($cache_id, $data, $expires = NULL);
	
	/**
   * Delete a cache entry or a group of entries.
   */
	public function delete($request_id, $is_pattern = FALSE);
	
	/**
   * Clear the entire cache.
   */
	public function clear();
}