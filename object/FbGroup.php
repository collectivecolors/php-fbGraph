<?php

/**
 * Require some needed classes.
 */
fb_graph_require('FbUserCollection', 'object', TRUE);


/**
 * Facebook Graph API group object.
 */
class FbGroup extends FbUserCollection {

  /**
   * Provide a text based name for this object class.
   */
  public function getType() {
    return 'group';
  }

  /*****************************************************************************
   * Facebook group properties (not all of these are public)
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