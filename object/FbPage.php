<?php

/**
 * Require some needed classes.
 */
fb_graph_require('FbSocialObject', 'object', TRUE);


/**
 * Facebook Graph API page object.
 */
class FbPage extends FbSocialObject {

  /**
   * Provide a text based name for this object class.
   */
  public function getType() {
    return 'page';
  }

  /*****************************************************************************
   * Facebook page properties (not all of these are public)
   */

  /**
   * Facebook page picture.
   */
  protected $picture;

  /**
   * Retrieve Facebook page picture.
   */
  public function getPicture() {
    return $this->picture;
  }

  /**
   * Set Facebook page picture.
   */
  protected function setPicture($picture) {
    $this->picture = $picture;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook page category.
   */
  protected $category;

  /**
   * Retrieve current Facebook page category.
   */
  public function getCategory() {
    return $this->category;
  }

  /**
   * Set Facebook page category.
   */
  protected function setCategory($category) {
    $this->category = $category;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook page fan count.
   */
  protected $fan_count;

  /**
   * Retrieve current Facebook page fan count.
   */
  public function getFanCount() {
    return $this->fan_count;
  }

  /**
   * Set Facebook page fan count.
   */
  protected function setFanCount($fan_count) {
    $this->fan_count = $fan_count;
  }

  /*****************************************************************************
   * Utilities
   */

  /**
   * Parse a Facebook page object into our FbPage object.
   */
  public function parse($data) {
    parent::parse($data);

    $this->setPicture($data['picture']);
    $this->setCategory($data['category']);
    $this->setFanCount($data['fan_count']);
  }
}