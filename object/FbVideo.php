<?php

/**
 * Require some needed classes.
 */
fb_graph_require('FbImageResource', 'object', TRUE);


/**
 * Facebook Graph API user video object.
 */
class FbVideo extends FbImageResource {

  /**
   * Provide a text based name for this object class.
   */
  public function getType() {
    return 'video';
  }

  /*****************************************************************************
   * Facebook user video properties (not all of these are public)
   */

  /**
   * Facebook user video length.
   */
  protected $length;

  /**
   * Retrieve Facebook user video length.
   */
  public function getLength() {
    return $this->length;
  }

  /**
   * Set Facebook user video length.
   */
  protected function setLength($length) {
    $this->length = $length;
  }

  /*****************************************************************************
   * Utilities
   */

  /**
   * Overrides: parent::parse().
   */
  public function parse($data) {
    parent::parse($data);

    $this->setLength($data['length']);
  }
}