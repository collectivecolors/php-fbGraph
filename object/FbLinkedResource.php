<?php

/**
 * Require some needed classes.
 */
fb_graph_require('FbUserSubmission', 'object', TRUE);


/**
 * Facebook Graph API linkable submission base object.
 */
abstract class FbLinkedResource extends FbUserSubmission {
  
  /*****************************************************************************
   * Facebook user submission properties (not all of these are public)
   */
  
  /**
   * Facebook user submission caption.
   */
  protected $caption;
  
  /**
   * Retrieve Facebook user submission caption.
   */
  public function getCaption() {
    return $this->caption;
  }
  
  /**
   * Set Facebook user submission caption.
   */
  protected function setCaption($caption) {
    $this->caption = $caption;
  }
   
  //----------------------------------------------------------------------------
  
  /**
   * Facebook user submission description.
   */
  protected $description;
  
  /**
   * Retrieve Facebook user submission description.
   */
  public function getDescription() {
    return $this->description;
  }
  
  /**
   * Set Facebook user submission description.
   */
  protected function setDescription($description) {
    $this->description = $description;
  }
  
  /*****************************************************************************
   * Utilities
   */
  
  /**
   * Overrides: parent::parse().
   */
  public function parse($data) {
    parent::parse($data);
    
    $this->setCaption($data['caption']); 
    $this->setDescription($data['description']); 
  }
}