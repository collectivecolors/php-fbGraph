<?php

/**
 * Require some needed classes.
 */
fb_graph_require('FbObject', 'object', TRUE);


/**
 * Facebook Graph API social base object.
 */
abstract class FbSocialObject extends FbObject {
  
  /*****************************************************************************
   * Facebook social properties (not all of these are public)
   */
  
  /**
   * Facebook social object likes.
   */
  protected $likes;
  
  /**
   * Retrieve Facebook social object likes.
   */
  public function getLikes() {
    return $this->likes;
  }
  
  /**
   * Set Facebook social object likes.
   */
  protected function setLikes($likes) {
    $this->likes = $likes;
  }
   
  /*****************************************************************************
   * Utilities
   */
  
  /**
   * Overrides: parent::parse().
   */
  public function parse($data) {
    parent::parse($data);
    
    $this->setLikes($data['likes']);  
  }
}