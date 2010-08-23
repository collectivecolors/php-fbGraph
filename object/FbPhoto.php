<?php

/**
 * Require some needed classes.
 */
fb_graph_require('FbImageResource', 'object', TRUE);


/**
 * Facebook Graph API user photo object.
 */
class FbPhoto extends FbImageResource {

  /**
   * Provide a text based name for this object class.
   */
  public function getType() {
    return 'photo';
  }

  /*****************************************************************************
   * Facebook user photo properties (not all of these are public)
   */

  /**
   * Facebook user photo tags.
   */
  protected $tags;

  /**
   * Retrieve Facebook user photo tags.
   */
  public function getTags() {
    return $this->tags;
  }

  /**
   * Set Facebook user photo tags.
   */
  protected function setTags($tags) {
    // TODO: Find out what the format of tags really is and format it correctly.
    $this->tags = $tags;
  }

  /*****************************************************************************
   * Utilities
   */

  /**
   * Overrides: parent::parse().
   */
  public function parse($data) {
    parent::parse($data);

    $this->setTags($data['tags']);
  }
}