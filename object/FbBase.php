<?php

fb_graph_require('LogObject', '', TRUE);

/**
 * Facebook Graph API base object.
 *
 * All objects extend this base class.
 */
abstract class FbBase extends LogObject {

  /**
   * Constructor.
   *
   * Initialize class variables.
   */
  public function __construct($data = array(), FbGraph $connector = NULL, $depth = 0) {
    $this->setConnector($connector);
    $this->setDepth($depth);
    $this->parse($data);
  }

  /*****************************************************************************
   * Facebook object properties.
   */

  /**
   * This property holds the Facebook Graph API connection reference.
   */
  protected $connector;

  /**
   * Set Graph API connection reference.
   */
  public function setConnector(FbGraph $connector) {
    $this->connector = $connector;
    
    if (!is_null($connector)) {
      $this->useLogObject($connector);
    }
  }

  //----------------------------------------------------------------------------

  /**
   * Parse depth of a Facebook Graph API request.
   *
   * See FbGraph class $depth property for more information.
   */
  protected $depth = 0;

  /**
   * Set parse depth for this object.
   */
  public function setDepth($depth) {
    $this->depth = $depth;
  }

  /**
   * Return the next depth level.  When depth equals zero we stop requesting
   * new data.
   */
  public function nextDepth() {
    return max($this->depth - 1, 0);
  }

  /**
   * Make sure depth is greater than zero.
   *
   * At or below zero, we no longer want to parse sub properties.
   */
  public function checkDepth() {
    return (!is_null($this->connector) && ($this->depth > 0));
  }

  /*****************************************************************************
   * Utilities
   */

  /**
   * Request more information about an object.
   */
  protected function request($data, $force = FALSE) {
  	$this->_debug('request()', $data, $force, $this->checkDepth());

    if (($force || $this->checkDepth())
        && $this->checkAssoc($data)
        && array_key_exists('id', $data)) {

      $this->_debug('Requesting object...', $data['id'], $this->nextDepth());

      // We have a Graph API connector and depth is good.
      $data = $this->connector->request($data['id'], '', $this->nextDepth());
      $this->_debug('Returned object:', $data);
    }
    return $data;
  }

  //----------------------------------------------------------------------------

  /**
   * Create a new instance of some object class given data.
   *
   * We probably only know the id and the name of the object when this is used.
   */
  protected function create($data) {
  	$this->_debug('create()', $data, $this->nextDepth());

  	$object = $this->request($data);
  	$this->_debug('Returning object...', $object);

  	return $object;
  }

  /**
   * Create a list of object instances given a list of objects.
   */
  protected function createList($data) {
  	$this->_debug('createList()', $data);

    $list = array();
    if (is_array($data) && !$this->checkAssoc($data)) {
      foreach ($data as $object) {
      	$this->_debug('List item:', $object);
        $list[] = $this->create($object);
      }
    }
    else {
    	$this->_debug('List data');
      $list[] = $this->create($data);
    }
    $this->_debug('Returning list...', $list);
    return $list;
  }

  //----------------------------------------------------------------------------

  /**
   * Check whether or not a given class is a valid object extension.
   */
  protected function checkExtension($class) {
    if (!class_exists($class)) {
      throw new Exception("$class is not an implemented object extension.");
    }
  }

  /**
   * Create a new instance of a given object extension class.
   */
  protected function createExtension($class, $data, $check = TRUE) {
  	$this->_debug('createExtension()', $class, $data, $check);

    if ($check) {
    	$this->_debug('Checking extension.');
      $this->checkExtension($class);
    }
    if (is_array($data) && $this->checkAssoc($data)) {
    	$this->_debug("Returning new $class...", $this->nextDepth());
      return new $class($data, $this->connector, $this->nextDepth());
    }
    $this->_debug('Returning NULL...');
    return NULL;
  }

  /**
   * Create a list of object extension instances given a list of objects.
   */
  protected function createExtensionList($class, $data) {
  	$this->_debug('createExtension()', $class, $data);

    $this->checkExtension($class);

    if (is_array($data)) {
      $list = array();

      if ($this->checkAssoc($data)) {
      	$this->_debug('Creating new extension...', $class);
        $list[] = $this->createExtension($class, $data, FALSE);
      }
      else {
        foreach ($data as $item) {
        	$this->_debug('List item:', $item);
          $list[] = $this->createExtension($class, $item, FALSE);
        }
      }
      $this->_debug('Returning list...', $list);
      return $list;
    }
    $this->_debug('Returning NULL...');
    return NULL;
  }

  //----------------------------------------------------------------------------

  /**
   * Parse a Facebook object into our class.
   *
   * This method should be overridden in all child object classes.
   */
  abstract public function parse($data);

  //----------------------------------------------------------------------------

  /**
   * Check if a given array is associative (TRUE) or sequential (FALSE).
   */
  public function checkAssoc($array){
    return $this->connector->checkAssoc($array);
  }
}