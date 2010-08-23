<?php

/**
 * Require some needed classes.
 */
fb_graph_require('FbSocialObject', 'object', TRUE);


/**
 * Facebook Graph API user submission base object.
 */
abstract class FbUserSubmission extends FbSocialObject {

  /*****************************************************************************
   * Facebook user submission properties (not all of these are public)
   */

  /**
   * Facebook user submission from.
   */
  protected $from;

  /**
   * Retrieve Facebook user submission from.
   */
  public function getFrom() {
    return $this->from;
  }

  /**
   * Set Facebook user submission from.
   */
  protected function setFrom($from) {
    $this->from = $this->create($from);
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user submission to.
   */
  protected $to;

  /**
   * Retrieve Facebook user submission to.
   */
  public function getTo() {
    return $this->to;
  }

  /**
   * Set Facebook user submission to.
   */
  protected function setTo($to) {
    // I believe this can be addressed to non users as well (page?).
    $this->to = $this->createList($to);
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user submission message.
   */
  protected $message;

  /**
   * Retrieve Facebook user submission message.
   */
  public function getMessage() {
    return $this->message;
  }

  /**
   * Set Facebook user submission message.
   */
  protected function setMessage($message) {
    $this->message = $message;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user submission icon.
   */
  protected $icon;

  /**
   * Retrieve Facebook user submission icon.
   */
  public function getIcon() {
    return $this->icon;
  }

  /**
   * Set Facebook user submission icon.
   */
  protected function setIcon($icon) {
    $this->icon = $icon;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user submission created time.
   */
  protected $created_time;

  /**
   * Retrieve Facebook user submission created time.
   */
  public function getCreatedTime() {
    // Not all submission types seem to have a created time but all seem to have
    // an updated time.
    return ($this->created_time ? $this->created_time : $this->getUpdatedTime());
  }

  /**
   * Set Facebook user submission created time.
   */
  protected function setCreatedTime($created_time) {
    $this->created_time = strtotime($created_time);
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user submission updated time.
   */
  protected $updated_time;

  /**
   * Retrieve Facebook user submission updated time.
   */
  public function getUpdatedTime() {
    return $this->updated_time;
  }

  /**
   * Set Facebook user submission updated time.
   */
  protected function setUpdatedTime($updated_time) {
    $this->updated_time = strtotime($updated_time);
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook object actions.
   */
  protected $actions; // name => link map

  /**
   * Retrieve Facebook object actions.
   */
  public function getActions() {
    return $this->actions;
  }

  /**
   * Set Facebook object actions.
   */
  protected function setActions($actions) {
    $this->actions = array();

    if (is_array($actions)) {
      foreach ($actions as $action) {
        $this->actions[$action['name']] = $action['link'];
      }
    }
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user submission application attribution.
   */
  protected $attribution;

  /**
   * Retrieve Facebook user submission application attribution.
   */
  public function getAttribution() {
    return $this->attribution;
  }

  /**
   * Set Facebook user submission application attribution.
   */
  protected function setAttribution($attribution) {
    $this->attribution = $attribution;
  }

  /*****************************************************************************
   * Utilities
   */

  /**
   * Overrides: parent::parse().
   */
  public function parse($data) {
    parent::parse($data);

    $this->setFrom($data['from']);
    $this->setTo($data['to']['data']);
    $this->setMessage($data['message']);
    $this->setIcon($data['icon']);
    $this->setCreatedTime($data['created_time']);
    $this->setUpdatedTime($data['updated_time']);
    $this->setActions($data['actions']);
    $this->setAttribution($data['attribution']);
  }
}