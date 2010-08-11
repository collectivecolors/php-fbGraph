<?php

/**
 * Require some needed classes.
 */
fb_graph_require('FbUserSubmission', 'object', TRUE);


/**
 * Facebook Graph API user note object.
 */
class FbNote extends FbUserSubmission {

  /**
   * Provide a text based name for this object class.
   */
  public function getType() {
    return 'note';
  }

  /*****************************************************************************
   * Facebook user note properties (not all of these are public)
   */

  /**
   * Facebook user note subject.
   */
  protected $subject;

  /**
   * Retrieve Facebook user note subject.
   */
  public function getSubject() {
    return $this->subject;
  }

  /**
   * Set Facebook user note subject.
   */
  protected function setSubject($subject) {
    $this->subject = $subject;
  }

  /*****************************************************************************
   * Utilities
   */

  /**
   * Overrides: parent::parse().
   */
  public function parse($data) {
    parent::parse($data);

    $this->setSubject($data['subject']);
  }
}