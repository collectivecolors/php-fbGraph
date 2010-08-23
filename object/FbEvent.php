<?php

/**
 * Require some needed classes.
 */
fb_graph_require('FbUserCollection', 'object', TRUE);


/**
 * Facebook Graph API event object.
 */
class FbEvent extends FbUserCollection {

  /**
   * Provide a text based name for this object class.
   */
  public function getType() {
    return 'event';
  }

  /*****************************************************************************
   * Facebook event properties (not all of these are public)
   */

  /**
   * Facebook event start time.
   */
  protected $start_time;

  /**
   * Retrieve Facebook event start time.
   */
  public function getStartTime() {
    return $this->start_time;
  }

  /**
   * Set Facebook event start time.
   */
  protected function setStartTime($start_time) {
    $this->start_time = strtotime($start_time);
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook event end time.
   */
  protected $end_time;

  /**
   * Retrieve Facebook event end time.
   */
  public function getEndTime() {
    return $this->end_time;
  }

  /**
   * Set Facebook event end time.
   */
  protected function setEndTime($end_time) {
    $this->end_time = strtotime($end_time);
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook event rsvp status.
   */
  protected $rsvp_status;

  /**
   * Retrieve Facebook event rsvp status.
   */
  public function getRsvpStatus() {
    return $this->rsvp_status;
  }

  /**
   * Set Facebook event rsvp status.
   */
  protected function setRsvpStatus($rsvp_status) {
    $this->rsvp_status = $rsvp_status;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user location.
   */
  protected $location;

  /**
   * Retrieve Facebook user location.
   */
  public function getLocation() {
    return $this->location;
  }

  /**
   * Set Facebook user location.
   */
  protected function setLocation($location) {
    // TODO: Not sure if this would ever be a Facebook page yet?
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

    $this->setStartTime($data['start_time']);
    $this->setEndTime($data['end_time']);
    $this->setRsvpStatus($data['rsvp_status']);
    $this->setLocation($data['location']);
  }
}