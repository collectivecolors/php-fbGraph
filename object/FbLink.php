<?php

/**
 * Require some needed classes.
 */
fb_graph_require('FbImageResource', 'object', TRUE);


/**
 * Facebook Graph API user link object.
 */
class FbLink extends FbImageResource {

  /**
   * Provide a text based name for this object class.
   */
  public function getType() {
    return 'link';
  }

  /*****************************************************************************
   * Facebook user link properties (not all of these are public)
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