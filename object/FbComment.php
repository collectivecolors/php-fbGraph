<?php

/**
 * Require some needed classes.
 */
fb_graph_require('FbObject', 'object', TRUE);


/**
 * Facebook Graph API user comment object.
 */
class FbComment extends FbObject {

  /**
   * Provide a text based name for this object class.
   */
  public function getType() {
    return 'comment';
  }

  /*****************************************************************************
   * Facebook user comment properties (not all of these are public)
   */

  /**
   * Facebook user comment from.
   */
  protected $from;

  /**
   * Retrieve Facebook user comment from.
   */
  public function getFrom() {
    return $this->from;
  }

  /**
   * Set Facebook user comment from.
   */
  protected function setFrom($from) {
    $this->from = $this->create($from);
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user comment message.
   */
  protected $message;

  /**
   * Retrieve Facebook user comment message.
   */
  public function getMessage() {
    return $this->message;
  }

  /**
   * Set Facebook user comment message.
   */
  protected function setMessage($message) {
    $this->message = $message;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user comment created time.
   */
  protected $created_time;

  /**
   * Retrieve Facebook user comment created time.
   */
  public function getCreatedTime() {
    return $this->created_time;
  }

  /**
   * Set Facebook user comment created time.
   */
  protected function setCreatedTime($created_time) {
    $this->created_time = strtotime($created_time);
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
    $this->setMessage($data['message']);
    $this->setCreatedTime($data['created_time']);
  }
}