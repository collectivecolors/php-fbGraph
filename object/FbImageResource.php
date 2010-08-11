<?php

/**
 * Require some needed classes.
 */
fb_graph_require('FbLinkedResource', 'object', TRUE);


/**
 * Facebook Graph API image based submission base object.
 */
abstract class FbImageResource extends FbLinkedResource {
  
  /*****************************************************************************
   * Facebook user submission properties (not all of these are public)
   */
  
  /**
   * Facebook user submission picture.
   */
  protected $picture;
  
  /**
   * Retrieve Facebook user submission picture.
   */
  public function getPicture() {
    return $this->picture;
  }
  
  /**
   * Set Facebook user submission picture.
   */
  protected function setPicture($picture) {
    $this->picture = $picture;
  }
  
  //----------------------------------------------------------------------------
  
  /**
   * Facebook user submission source.
   */
  protected $source;
  
  /**
   * Retrieve Facebook user submission source.
   */
  public function getSource() {
    return $this->source;
  }
  
  /**
   * Set Facebook user submission source.
   */
  protected function setSource($source) {
    $this->source = $source;
  }
  
  /*****************************************************************************
   * Utilities
   */
  
  /**
   * Overrides: parent::parse().
   */
  public function parse($data) {
    parent::parse($data);
    
    $this->setPicture($data['picture']);
    $this->setSource($data['source']);
  }
}