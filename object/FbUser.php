<?php

/**
 * Require some needed classes.
 */
fb_graph_require('FbObject', 'object', TRUE);


/**
 * Facebook Graph API user object.
 */
class FbUser extends FbObject {

  /**
   * Provide a text based name for this object class.
   */
  public function getType() {
    return 'user';
  }

  /*****************************************************************************
   * Facebook user properties (not all of these are public)
   */

  /**
   * Facebook user first name.
   */
  protected $first_name;

  /**
   * Retrieve current Facebook user first name.
   */
  public function getFirstName() {
    return $this->first_name;
  }

  /**
   * Set Facebook user first name.
   */
  protected function setFirstName($first_name) {
    $this->first_name = $first_name;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user last name.
   */
  protected $last_name;

  /**
   * Retrieve current Facebook user last name.
   */
  public function getLastName() {
    return $this->last_name;
  }

  /**
   * Set Facebook user first name.
   */
  protected function setLastName($last_name) {
    $this->last_name = $last_name;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user email.
   */
  protected $email;

  /**
   * Retrieve current Facebook user email.
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * Set Facebook user email.
   */
  protected function setEmail($email) {
    $this->email = $email;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user website.
   */
  protected $website;

  /**
   * Retrieve current Facebook user website.
   */
  public function getWebsite() {
    return $this->website;
  }

  /**
   * Set Facebook user website.
   */
  protected function setWebsite($website) {
    $this->website = $website;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user timezone offset.
   */
  protected $timezone_offset;

  /**
   * Retrieve Facebook user timezone offset.
   */
  public function getTimezoneOffset() {
    return $this->timezone_offset;
  }

  /**
   * Set Facebook user timezone offset.
   */
  protected function setTimezoneOffset($timezone_offset) {
    $this->timezone_offset = $timezone_offset;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user gender.
   */
  protected $gender;

  /**
   * Retrieve current Facebook user gender.
   */
  public function getGender() {
    return $this->gender;
  }

  /**
   * Set Facebook user gender.
   */
  protected function setGender($gender) {
    $this->gender = $gender;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user birthday.
   */
  protected $birthday;

  /**
   * Retrieve current Facebook user birthday.
   */
  public function getBirthday() {
    return $this->birthday;
  }

  /**
   * Set Facebook user birthday.
   */
  protected function setBirthday($birthday) {
    $this->birthday = $birthday;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user relationship status.
   */
  protected $relationship_status;

  /**
   * Retrieve current Facebook user relationship status.
   */
  public function getRelationshipStatus() {
    return $this->relationship_status;
  }

  /**
   * Set Facebook user relationship status.
   */
  protected function setRelationshipStatus($relationship_status) {
    $this->relationship_status = $relationship_status;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user religion.
   */
  protected $religion;

  /**
   * Retrieve current Facebook user religion.
   */
  public function getReligion() {
    return $this->religion;
  }

  /**
   * Set Facebook user religion.
   */
  protected function setReligion($religion) {
    $this->religion = $this->create($religion);
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user political affiliation.
   */
  protected $political_affiliation;

  /**
   * Retrieve current Facebook user political affiliation.
   */
  public function getPoliticalAffiliation() {
    return $this->political_affiliation;
  }

  /**
   * Set Facebook user political affiliation.
   */
  protected function setPoliticalAffiliation($political_affiliation) {
    $this->political_affiliation = $this->create($political_affiliation);
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user location.
   */
  protected $location;

  /**
   * Retrieve current Facebook user location.
   */
  public function getLocation() {
    return $this->location;
  }

  /**
   * Set Facebook user location.
   */
  protected function setLocation($location) {
    $this->location = $this->create($location);
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user hometown.
   */
  protected $hometown;

  /**
   * Retrieve current Facebook user hometown.
   */
  public function getHometown() {
    return $this->hometown;
  }

  /**
   * Set Facebook user hometown.
   */
  protected function setHometown($hometown) {
    $this->hometown = $this->create($hometown);
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user work history.
   */
  protected $work_history;

  /**
   * Retrieve Facebook user work history.
   */
  public function getWorkHistory() {
    return $this->work_history;
  }

  /**
   * Set Facebook user work history.
   */
  protected function setWorkHistory($work_history) {
    $this->work_history = $this->createExtensionList('FbJob', $work_history);
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user work education.
   */
  protected $education;

  /**
   * Retrieve Facebook user education.
   */
  public function getEducation() {
    return $this->education;
  }

  /**
   * Set Facebook user education.
   */
  protected function setEducation($education) {
    $this->education = $this->createExtensionList('FbStudy', $education);
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user verified.
   */
  protected $verified;

  /**
   * Retrieve Facebook user verified.
   */
  public function getVerified() {
    return $this->verified;
  }

  /**
   * Set Facebook user verified.
   */
  protected function setVerified($verified) {
    $this->verified = $verified;
  }

  /*****************************************************************************
   * Utilities
   */

  /**
   * Parse a Facebook user object into our FbUser object.
   */
  public function parse($data) {
    parent::parse($data);

    $this->setFirstName($data['first_name']);
    $this->setLastName($data['last_name']);
    $this->setEmail($data['email']);
    $this->setWebsite($data['website']);
    $this->setTimezoneOffset($data['timezone']);
    $this->setGender($data['gender']);
    $this->setBirthday($data['birthday']);
    $this->setRelationshipStatus($data['relationship_status']);
    $this->setReligion($data['religion']);
    $this->setPoliticalAffiliation($data['political']);
    $this->setLocation($data['location']);
    $this->setHometown($data['hometown']);
    $this->setWorkHistory($data['work']);
    $this->setEducation($data['education']);
    $this->setVerified($data['verified']);
  }
}


/**
 * Facebook Graph API user job object (part of work history, see FbUser).
 */
class FbJob extends FbBase {

  /*****************************************************************************
   * Facebook job properties.
   */

  /**
   * Facebook user employer.
   */
  protected $employer;

  /**
   * Retrieve Facebook user employer.
   */
  public function getEmployer() {
    return $this->employer;
  }

  /**
   * Set Facebook user employer.
   */
  protected function setEmployer($employer) {
    $this->employer = $this->create($employer);
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user position.
   */
  protected $position;

  /**
   * Retrieve current Facebook user position.
   */
  public function getPosition() {
    return $this->position;
  }

  /**
   * Set Facebook user position.
   */
  protected function setPosition($position) {
    $this->position = $this->create($position);
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook job start date.
   */
  protected $start_date;

  /**
   * Retrieve Facebook job start date.
   */
  public function getStartDate() {
    return $this->start_date;
  }

  /**
   * Set Facebook job start date.
   */
  protected function setStartDate($start_date) {
    $this->start_date = $start_date;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook job end date.
   */
  protected $end_date;

  /**
   * Retrieve Facebook job end date.
   */
  public function getEndDate() {
    return $this->end_date;
  }

  /**
   * Set Facebook job end date.
   */
  protected function setEndDate($end_date) {
    $this->end_date = $end_date;
  }

  /*****************************************************************************
   * Utilities
   */

  /**
   * Parse a Facebook object into our class.
   */
  public function parse($data) {
    $this->setEmployer($data['employer']);
    $this->setPosition($data['position']);
    $this->setStartDate($data['start_date']);
    $this->setEndDate($data['end_date']);
  }
}


/**
 * Facebook Graph API user study object (part of education, see FbUser).
 */
class FbStudy extends FbBase {

  /*****************************************************************************
   * Facebook study properties.
   */

  /**
   * Facebook user school.
   */
  protected $school;

  /**
   * Retrieve Facebook user school.
   */
  public function getSchool() {
    return $this->school;
  }

  /**
   * Set Facebook user school.
   */
  protected function setSchool($school) {
    $this->school = $this->create($school);
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user graduation/last year.
   */
  protected $year;

  /**
   * Retrieve Facebook user graduation/last year.
   */
  public function getYear() {
    return $this->year;
  }

  /**
   * Set Facebook user graduation/last year.
   */
  protected function setYear($year) {
    $this->year = $this->create($year);
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook user concentration.
   */
  protected $concentration;

  /**
   * Retrieve Facebook user concentration(s).
   */
  public function getConcentration() {
    return $this->concentration;
  }

  /**
   * Set Facebook user concentration(s).
   */
  protected function setConcentration($concentration) {
    $this->concentration = $this->createList($concentration);
  }

  /*****************************************************************************
   * Utilities
   */

  /**
   * Parse a Facebook object into our class.
   */
  public function parse($data) {
    $this->setSchool($data['school']);
    $this->setYear($data['year']);
    $this->setConcentration($data['concentration']);
  }
}
