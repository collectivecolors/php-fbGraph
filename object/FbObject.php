<?php

/**
 * Require some needed classes.
 */
fb_graph_require('FbBase', 'object', TRUE);

/**
 * Facebook Graph API base object.
 *
 * All other objects extend this base class.
 */
abstract class FbObject extends FbBase {

  /**
   * Return the type of a specific object for the class type.
   */
  abstract public function getType();

  /*****************************************************************************
   * Facebook object properties.
   */

  /**
   * Array of connections (relationships) for this object.
   *
   * These may be fed into the FbGraph request method to get related content.
   */
  protected $connections;

  /**
   * Retrieve current connection options for this object.
   */
  public function getConnections() {
    return $this->connections;
  }

  protected function setConnections($connections) {
    $this->connections = array_keys($connections);
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user id.
   */
  protected $id;

  /**
   * Retrieve current Facebook user id.
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set Facebook user id.
   */
  protected function setId($id) {
    $this->id = $id;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook object name.
   */
  protected $name;

  /**
   * Retrieve current Facebook object name.
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Set Facebook object name.
   */
  protected function setName($name) {
    $this->name = $name;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook link.
   */
  protected $link;

  /**
   * Retrieve Facebook link.
   */
  public function getLink() {
    return $this->link;
  }

  /**
   * Set Facebook link.
   */
  protected function setLink($link) {
    $this->link = $link;
  }
  
  /*****************************************************************************
   * Utilities
   */

  /**
   * Parse a Facebook object into our class.
   *
   * This method should be overridden in all child object classes.
   */
  public function parse($data) {
  	$this->_debug('parse()', $data);

    if (is_array($data['metadata'])) {
      $this->setConnections($data['metadata']['connections']);
    }
    $this->setId($data['id']);
    $this->setName($data['name']);
    $this->setLink($data['link']);
  }
}