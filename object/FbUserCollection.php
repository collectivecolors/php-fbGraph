<?php

/**
 * Require some needed classes.
 */
fb_graph_require('FbSocialObject', 'object', TRUE);


/**
 * Facebook Graph API user collection base object.
 */
abstract class FbUserCollection extends FbSocialObject {

  /*****************************************************************************
   * Facebook user collection properties (not all of these are public)
   */

  /**
   * Facebook user collection owner / creator.
   */
  protected $owner;

  /**
   * Retrieve current Facebook user collection owner / creator.
   */
  public function getOwner() {
    return $this->owner;
  }

  /**
   * Set Facebook user collection owner / creator.
   */
  protected function setOwner($owner) {
    $this->owner = $this->create($owner);
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user collection description.
   */
  protected $description;

  /**
   * Retrieve current Facebook user collection description.
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * Set Facebook user collection description.
   */
  protected function setDescription($description) {
    $this->description = $description;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user collection privacy.
   */
  protected $privacy;

  /**
   * Retrieve current Facebook user collection privacy.
   */
  public function getPrivacy() {
    return $this->privacy;
  }

  /**
   * Set Facebook user collection privacy.
   */
  protected function setPrivacy($privacy) {
    $this->privacy = $privacy;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user collection updated time.
   */
  protected $updated_time;

  /**
   * Retrieve current Facebook user collection updated time.
   */
  public function getUpdatedTime() {
    return $this->updated_time;
  }

  /**
   * Set Facebook user collection updated time.
   */
  protected function setUpdatedTime($updated_time) {
    $this->updated_time = strtotime($updated_time);
  }

  /*****************************************************************************
   * Utilities
   */

  /**
   * Overrides: parent::parse().
   */
  public function parse($data) {
    parent::parse($data);

    $this->setOwner($data['owner']);
    $this->setDescription($data['description']);
    $this->setPrivacy($data['privacy']);
    $this->setUpdatedTime($data['updated_time']);
  }
}