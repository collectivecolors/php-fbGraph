<?php

/**
 * Require some needed classes.
 */
fb_graph_require('FbImageResource', 'object', TRUE);


/**
 * Facebook Graph API user post object.
 */
class FbPost extends FbImageResource {

  /**
   * Provide a text based name for this object class.
   */
  public function getType() {
    return 'post';
  }

  /*****************************************************************************
   * Facebook user post properties (not all of these are public)
   */

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

  /*****************************************************************************
   * Utilities
   */

  /**
   * Overrides: parent::parse().
   */
  public function parse($data) {
    parent::parse($data);

    $this->setPrivacy($data['privacy']);
  }
}