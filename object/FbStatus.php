<?php

/**
 * Require some needed classes.
 */
fb_graph_require('FbUserSubmission', 'object', TRUE);


/**
 * Facebook Graph API user status message object.
 */
class FbStatus extends FbUserSubmission {

  /**
   * Provide a text based name for this object class.
   */
  public function getType() {
    return 'status';
  }

  /*****************************************************************************
   * Facebook user status message properties (not all of these are public)
   */

	// Nothing here.  This is basically a vanity class right now.

  /*****************************************************************************
   * Utilities
   */

  /**
   * Overrides: parent::parse().
   */
  public function parse($data) {
    parent::parse($data);

    // Nothing to parse.
  }
}