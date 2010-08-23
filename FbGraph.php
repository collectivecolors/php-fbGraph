<?php

/**
 * Set up a package include structure.
 */
function fb_graph_require($class, $type = '', $is_core = TRUE) {
  // This is the only global variable.  I promise!
  global $fb_graph_base_path;

  $package_dir = (!$is_core && isset($fb_graph_base_path)
  ? $fb_graph_base_path
  : dirname(__FILE__)
  );

  if (strlen($type)) {
    require_once("$package_dir/$type/$class.php");
  }
  else {
    require_once("$package_dir/$class.php");
  }
}

fb_graph_require('ICache', 'interface', TRUE);
fb_graph_require('StaticCache', 'cache', TRUE);

fb_graph_require('LogObject', '', TRUE);


/**
 * Facebook Graph API connectivity layer.
 */
class FbGraph extends LogObject {

  /**
   * Constructor.
   *
   * Initialize class variables.
   */
  public function __construct($apikey = NULL, $app_secret = NULL, $url = NULL) {
    $this->setApiKey($apikey);
    $this->setAppSecret($app_secret);

    $this->setSearchUrl($url);
    $this->setCache(); // Set to default.  We definitely need caching going.
  }

  //----------------------------------------------------------------------------

  /**
   * Require the Facebook PHP SDK.
   *
   * This is only needed if this library is used outside of the
   * Drupal for Facebook project.
   */
  public function requireFacebook($path = NULL) {
    if (is_null($path)) {
      // Check the package directory.
      require_once(dirname(__FILE__) . '/facebook.php');
    }
    else {
      require_once($path);
    }
    // Initialize the Facebook instance.
    $this->init();
  }

  //----------------------------------------------------------------------------

  /**
   * Initialization state variable.
   */
  protected $initialized = FALSE;

  /**
   * Initialize the Facebook instance.
   *
   * This is called automatically after including the Facebook library or it
   * can be called separately if the Facebook SDK has been included by another
   * library already, such as the case with Drupal for Facebook.
   */
  public function init($apikey = NULL, $app_secret = NULL) {
    $this->_debug('init()', $apikey, $app_secret);

    if (!class_exists('Facebook')) {
      return; //throw new Exception('Facebook PHP SDK is required to access the Facebook Graph API.');
    }
    if (is_null($this->fb)) {
      // Make sure we have an API key.
      if (strlen($apikey)) {
        $this->setApiKey($apikey);
      }
      elseif (!$this->getApiKey()) {
        throw new Exception('Facebook application API key is required to initiate a Graph API connection.');
      }

      // Make sure we have a application secret.
      if (strlen($app_secret)) {
        $this->setAppSecret($app_secret);
      }
      elseif (!$this->getAppSecret()) {
        throw new Exception('Facebook application secret is required to initiate a Graph API connection.');
      }

      // Get a new anonymous Facebook instance for this application.
      $this->fb = new Facebook(
      array(
          'appId'  => $this->getApiKey(),
          'secret' => $this->getAppSecret(),
          'cookie' => FALSE,
      ));
      // Facebook doesn't seem to have valid certificates.
      Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = FALSE;
      Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYHOST] = FALSE;
    }
    $this->_debug('Facebook:', $this->fb);
    $this->initialized = TRUE;
  }

  /*****************************************************************************
   * Connectivity properties
   */

  /**
   * This property holds the Facebook reference for this class instance.
   */
  protected $fb;

  /**
   * Boolean flag that represents if a custom Facebook instance is being used.
   *
   * If so, then we do not bother creating an access_token in api calls and
   * rely on the session provided by the alternate Facebook instance being used.
   */
  protected $fb_alt = FALSE;

  /**
   * Set Facebook SDK instance.
   *
   * This is in case we want to run these graph queries as authenticated users.
   */
  public function setFacebook($fb) {
    $this->fb     = $fb;
    $this->fb_alt = (is_null($fb) ? FALSE : TRUE);

    // Initialize the Facebook instance.
    $this->init();
    $this->getAccessToken(TRUE);
  }

  //----------------------------------------------------------------------------

  /**
   * OAuth authentication token. (if we are using the anonymous connector)
   */
  protected $access_token;

  /**
   * Get a public Facebook authorization token.
   */
  public function getAccessToken($reset = FALSE) {
    $this->_debug('getAccessToken()', $reset);

    // If an old access token exists and we don't want to reset
    // then return the existing one.
    if (!$reset && $this->access_token) {
      $this->_debug('Already have access token:', $this->access_token);
      return $this->access_token;
    }
    if (!is_null($this->fb)) {
      // We need a valid access token to connect (OAuth 2.0).
      if ($this->fb_alt && ($session = $this->fb->getSession())) {
        $this->_debug('Existing Facebook session:', $session);
        $token = $session['access_token'];
      }
      else {
        $url = 'https://graph.facebook.com/oauth/access_token'
        . '?client_id=' . $this->fb->getAppId()
        . '&client_secret=' . $this->fb->getApiSecret()
        . '&type=client_cred';

        $this->_debug('Url:', $url);
        $result = drupal_http_request($url);
        $this->_debug('HTTP result:', $result, $result->data);

        $data  = explode('=', $result->data);
        $token = $data[1];
      }
      if ($token) {
        $this->_debug('Setting access token');
        $this->access_token = $token;
      }
      $this->_debug('Final access token:', $this->access_token);
    }
    return $this->access_token;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook application API key.
   */
  protected $apikey;

  /**
   * Get current Facebook application API key.
   */
  public function getApiKey() {
    return $this->apikey;
  }

  /**
   * Set Facebook application API key.
   */
  public function setApiKey($key) {
    $this->apikey = $key;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook application API key.
   */
  protected $app_secret;

  /**
   * Get current Facebook application API key.
   */
  public function getAppSecret() {
    return $this->app_secret;
  }

  /**
   * Set Facebook application API key.
   */
  public function setAppSecret($secret) {
    $this->app_secret = $secret;
  }

  //----------------------------------------------------------------------------

  /**
   * Facebook Graph search url.
   */
  const DEFAULT_SEARCH_URL = 'https://graph.facebook.com/search';

  protected $search_url = self::DEFAULT_SEARCH_URL;

  /**
   * Get the current Facebook Graph search url.
   */
  public function getSearchUrl() {
    return $this->search_url;
  }

  /**
   * Set the Graph search url.
   *
   * Just in case Facebook changes it.  Imagine that!
   */
  public function setSearchUrl($url = NULL) {
    if (is_null($url)) {
      $this->search_url = self::DEFAULT_SEARCH_URL;
    }
    else {
      $this->search_url = $url;
    }
  }

  //----------------------------------------------------------------------------

  /**
   * Parse depth of a Facebook Graph API request.
   *
   * If you specify a depth of 0 (the default) then the parser will parse
   * Facebook objects that are returned directly from Facebook services only.
   * If you choose to increase the depth, the objects will be parsed internally
   * as well.  For example, say you are requesting a user.  If that user has
   * specified a location that links to a Page for that location, then the Page
   * would be parsed into a local object as well.
   *
   * Essentially, the greater the depth, the fuller the tree of data returned.
   *
   * Although I have taken steps to avoid repeating requests for the same data
   * and parsing the same object, use this with care.  The magitude of objects
   * that could be returned may affect your system and Facebooks servers.
   */
  protected $depth = 0;

  /**
   * Get current parse depth for requests from this connector.
   */
  public function getDepth() {
    return $this->depth;
  }

  /**
   * Set parse depth for requests from this connector.
   */
  public function setDepth($depth) {
    $this->depth = $depth;
  }

  /*****************************************************************************
   * Connectivity services (AKA: The meat and potatos of this class)
   */

  /**
   * Get a unique request id for a given object id and optional connection.
   */
  public function getRequestId($obj_id, $connection = '') {
    return "$obj_id|$connection";
  }

  /**
   * Get a object id and an optional connection from a unique request id.
   */
  public function splitRequestId($request_id) {
    return explode('|', $request_id);
  }

  //----------------------------------------------------------------------------

  /**
   * Cache delegate.
   *
   * All cache entries are keyed by the RequestId() so you can implement your
   * own with these unique keys.  One case for implementing your own caching
   * method is to take advantage of persistent caching of objects.  The default
   * cach class "StaticCache" only statically caches the results.
   *
   * Or you might want to have multiple FbGraph instances sharing the same
   * cache.
   */
  protected $cache;

  /**
   * Set cache delegate.
   */
  public function setCache(ICache $cache = NULL) {
    if (is_null($cache)) {
      $this->cache = new StaticCache();
    }
    else {
      $this->cache = $cache;
    }
  }

  /**
   * Cached objects lifetime measured in seconds.
   *
   * This property is primarily useful for those cache classes that implement
   * some kind of persistent data storage that must be cleaned from time to
   * time.
   */
  protected $cache_lifetime;

  /**
   * Return current cache lifetime.
   *
   * This only represents the amount of time a brand new object would have.
   */
  public function getCacheLifetime() {
    return $this->cache_lifetime;
  }

  /**
   * Get the expiration timestamp for a new cache object.
   *
   * This is passed in to the cache get method.  It is possible that the cache
   * class ignores this parameter if it implements its own more curve fitting
   * cache lifetimes.
   */
  public function getCacheExpiration() {
    if ($lifetime = $this->getCacheLifetime()) {
      return time() + $lifetime; // Now plus number of seconds.
    }
    return NULL;
  }

  /**
   * Set the cache lifetime in seconds.
   *
   * This setting may be ignored by the cache class.
   */
  public function setCacheLifetime($seconds) {
    $this->cache_lifetime = $seconds;
  }

  //----------------------------------------------------------------------------

  /**
   * Retrieve a Facebook Graph object or series of related objects.
   */
  public function request($id, $connection = '', $depth = NULL, $expire = NULL) {
    $this->_fdebug('request()', $id, $connection, $depth, $expire);

    static $calls = 0; // Debug
    static $new = 0; // Debug

    $this->_debug('Requests: ' . ++$calls);

    if (!$id || is_null($this->fb)) {
      return NULL;
    }

    if (!$this->initialized) {
      $this->_error('Not initialized in FbGraph->request()');
      throw new Exception('FbGraph instance not initialized.  You need to call $obj->init() first.');
    }
    $request_id = $this->getRequestId($id, $connection);
    $this->_debug("Request id: $request_id");

    $object = $this->cache->get($request_id);
    if (is_null($object)) {
      $this->_debug('No cached object.');
      $params = array('access_token' => $this->getAccessToken());

      if (strlen($connection)) {
        $connection = "/$connection";
      }
      else {
        // Retrieve all available connections with the object properties.
        $params['metadata'] = 1;
      }
      $this->_debug('Facebook service calls: ' . ++$new);

      $this->_debug('Requesting...', "/$id$connection", $params);
      $object = $this->fb->api("/$id$connection", 'GET', $params);
      $expire = ($expire ? $expire : $this->getCacheExpiration());

      $this->_debug('Caching object...', $object, $expire);
      $this->cache->add($request_id, $object, $expire); // Cache in native format.
    }

    $this->_fdebug('Returning object...', $object);
    return $this->parseObject($object, $depth); // TODO: Figure out some cache system for object classes?
  }

  //----------------------------------------------------------------------------

  /**
   * Search constants
   */
  const SEARCH_TYPE_POST  = 'post';
  const SEARCH_TYPE_USER  = 'user';
  const SEARCH_TYPE_GROUP = 'group';
  const SEARCH_TYPE_PAGE  = 'page';
  const SEARCH_TYPE_EVENT = 'event';

  /**
   * Supported search types.
   */
  protected $supported_types = array(
  self::SEARCH_TYPE_POST => 'Posts',
  self::SEARCH_TYPE_USER => 'Users',
  self::SEARCH_TYPE_GROUP => 'Groups',
  self::SEARCH_TYPE_PAGE => 'Pages',
  self::SEARCH_TYPE_EVENT => 'Events',
  );

  /**
   * Return supported Facebook search types.
   */
  public function getSearchTypes() {
    return $this->supported_types;
  }

  /**
   * Retrieve Facebook object ids based on search criteria.
   */
  public function search($query, $type = self::SEARCH_TYPE_USER, $limit = NULL, $offset = NULL, $depth = NULL, $expire = NULL) {
    $this->_fdebug('search()', $query, $type, $limit, $offset, $depth, $expire);

    if (is_null($this->fb)) {
      return NULL;
    }
    if (!$this->initialized) {
      $this->_error('Not initialized in FbGraph->search()');
      throw new Exception('FbGraph instance not initialized.  You need to call $obj->init() first.');
    }

    $params = array('access_token' => $this->getAccessToken());

    if (strlen($query)) {
      $params['q'] = $query;
    }
    if (in_array($type, array_keys($this->supported_types))) {
      $params['type'] = $type;
    }
    if (is_int($limit) && $limit > 0) {
      $params['limit'] = $limit;
    }
    if (is_int($offset) && $offset > 0) {
      $params['offset'] = $offset;
    }

    $url = url($this->getSearchUrl(), array(
      'query' => $params,
    ));
    $this->_debug('Search location:', $this->getSearchUrl(), $params, $url);

    $http = drupal_http_request($url);
    $this->_debug('HTTP search response:', $http);

    if ($http->data) {
      $data = json_decode($http->data, TRUE);
      $this->_fdebug('Decoded data:', $data);

      if (is_array($data)) {
        if (isset($data['error_code'])) {
          $this->_error('We got error code ' . $data['error_code'] . ' from Facebook search.');
          throw new FacebookApiException($data);
        }
      }
      return $this->parseSearchResults($data, $depth, $expire);
    }
    $this->_fdebug('Returning NULL...');
    return NULL;
  }

  /*****************************************************************************
   * Data Parsers
   */

  /**
   * Parse a Facebook object into its defined class.
   *
   * This parser serves as a base for the Graph API requests.  This method may
   * throw the result to the parseList(...) method if it detects it is dealing
   * with a collection of object instances, not a single object.
   *
   * Supported classes:
   *
   * User centric classes:
   *  - FbUser
   *  - FbGroup
   *  - FbPage
   *  - FbEvent
   *
   * Text based classes:
   *  - FbLink
   *  - FbNote
   *  - FbPost
   *  - FbStatus
   *
   * Multimedia classes:
   *  - FbAlbum
   *  - FbPhoto
   *  - FbVideo
   */
  protected function parseObject($data, $depth = NULL) {
    $this->_debug('parseObject()', $data, $depth);

    if (is_null($depth)) {
      $depth = $this->getDepth();
    }
    $this->_debug("Depth: $depth");

    // Check to see if we have multiple items.
    if (!isset($data['id']) && is_array($data['data'])) {
      $this->_debug('Parsing a list...');
      return $this->parseList($data, $depth);
    }
    // We "should" have a single object to parse now.

    // We may have a custom class for this object.
    if ($data['type'] && ($class = $this->getObjectClass($data['type']))) {
      $this->_debug('Returning new class...', $class, $depth);
      return new $class($data, $this, $depth);
    }
    // If all else fails, just leave it.
    $this->_debug('Returning data...', $data);
    return $data;
  }

  /**
   * Parse a list (could be mixed) of Facebook objects to their defined classes.
   *
   * For supported classes, see parseObject() comments.
   */
  protected function parseList($data, $depth = NULL) {
    $this->_debug('parseList()', $data, $depth);

    if (isset($data['data']) && is_array($data['data'])) {
      $list = array();

      foreach ($data['data'] as $item) {
        $this->_debug('List item:', $item);
        $list[] = $this->parseObject($item, $depth);
      }
      $this->_debug('Returning list...', $list);
      return $list;
    }
    // If all else fails, just leave it.
    $this->_debug('Returning data...', $data);
    return $data;
  }

  /**
   * Parse search results.
   *
   * These results will probably have three fields:
   *  - Object name
   *  - Category?
   *  - Facebook ID (what we really need to request objects)
   */
  protected function parseSearchResults($data, $depth = NULL, $expire = NULL) {
    $this->_debug('parseSearchResults()', $data, $depth);

    if (is_null($depth)) {
      $depth = $this->getDepth();
    }
    $this->_debug("Depth: $depth");

    if (isset($data['data']) && is_array($data['data'])) {
      $list = array();

      foreach ($data['data'] as $item) {
        $this->_debug('Search result:', $item);

        // If we have depth set recurse into returned objects.
        if ($depth && array_key_exists('id', $item)) {
          $this->_debug('Requesting object...', $item['id']);
          $item = $this->request($item['id'], '', $depth, $expire);
          $this->_debug('Returned object:', $item);
        }
        else {
          $item = $this->parseObject($item);
        }
        $list[] = $item;
      }
      $this->_debug('Returning list...', $list);
      return $list;
    }
    // If all else fails, just leave it.
    $this->_debug('Returning data...', $data);
    return $data;
  }

  /*****************************************************************************
   * Utilities
   */

  /**
   * Map of Facebook type names to implemented object classes.
   */
  protected $object_map = array(
    'user' => 'FbUser',
    'group' => 'FbGroup',
    'event' => 'FbEvent',
    'page' => 'FbPage',
    'link' => 'FbLink',
    'post' => 'FbPost',
    'note' => 'FbNote',
    'status' => 'FbStatus',
    'album' => 'FbAlbum',
    'photo' => 'FbPhoto',
    'video' => 'FbVideo',
    'comment' => 'FbComment',
  );

  /**
   * Return class for given object type or NULL if no class was found.
   *
   * All calls for object classes should go through this method.
   */
  public function getObjectClass($type) {
    if (array_key_exists($type, $this->object_map)) {
      $class = $this->object_map[$type];

      fb_graph_require($class, 'object');
      return $class;
    }
    return NULL;
  }

  /**
   * Check if a given object class exists in the object map.
   */
  public function checkObjectClass($class) {
    if (in_array($class, $this->object_map)) {
      fb_graph_require($class, 'object');
      return TRUE;
    }
    return FALSE;
  }

  //----------------------------------------------------------------------------

  /**
   * Check if a given array is associative (TRUE) or sequential (FALSE).
   */
  public function checkAssoc($array){
    if (is_array($array)) {
      foreach(array_keys($array) as $key => $value) {
        if ($key != $value) {
          return TRUE;
        }
      }
    }
    return FALSE;
  }
}
