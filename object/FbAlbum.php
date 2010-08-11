<?php

/**
 * Require some needed classes.
 */
fb_graph_require('FbLinkedResource', 'object', TRUE);


/**
 * Facebook Graph API user photo album object.
 */
class FbAlbum extends FbLinkedResource {

  /**
   * Provide a text based name for this object class.
   */
  public function getType() {
    return 'album';
  }

  /*****************************************************************************
   * Facebook user photo album properties (not all of these are public)
   */

  /**
   * Facebook user photo album photo count.
   */
  protected $count;

  /**
   * Retrieve Facebook user photo album photo count.
   */
  public function getCount() {
    return $this->count;
  }

  /**
   * Set Facebook user photo album photo count.
   */
  protected function setCount($count) {
    $this->count = $count;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user photo album privacy.
   */
  protected $privacy;

  /**
   * Retrieve Facebook user photo album privacy.
   */
  public function getPrivacy() {
    return $this->privacy;
  }

  /**
   * Set Facebook user photo album privacy.
   */
  protected function setPrivacy($privacy) {
    $this->privacy = $privacy;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user photo album location.
   */
  protected $location;

  /**
   * Retrieve Facebook user photo album location.
   */
  public function getLocation() {
    return $this->location;
  }

  /**
   * Set Facebook user photo album location.
   */
  protected function setLocation($location) {
  	// TODO: Don't really know what this is?
    $this->location = $this->create($location);
  }

  /*****************************************************************************
   * Utilities
   */

  /**
   * Overrides: parent::parse().
   */
  public function parse($data) {
    parent::parse($data);

    $this->setCount($data['count']);
    $this->setPrivacy($data['privacy']);
    $this->setLocation($data['location']);
  }
}