Facebook Graph API interface
============================

**This library is in it's infancy and should be used with caution.**


The fbGraph library is an initial attempt at a client side interface for working
with Facebook data through the Graph API.  Currently only fetching and searching
for objects is supported.  That's what I initially needed it for.  The idea for
this library is to make it a lot easier to interact with Facebook.  Facebook 
provides a client side SDK that this library depends on.  You can find it 
[here][SDK].  

This SDK is a good start but I want something that makes it a lot easier to 
retrieve not just a Facebook object but related objects as well from the same 
method call.  This way we can pull trees of related data from Facebook.  The 
depth of the tree is specified by setting a depth property.  In order to 
faciliate a dynamic tree based request architecture without hammering Facebook's
servers with the same requests, I have implemented an extensible client side 
caching mechanism included in the fbGraph project.

I also want to wrap all objects returned in a class hierarchy so it becomes 
easier to validate and access available data.  The current structure is as close
as I can make it based on the information I have available (documentation and 
API result parsing).  I am probably way off here.  I really need to work with 
Facebook on this.

[SDK]: http://github.com/facebook/php-sdk


Structure
---------

So far this library consists of a main **FbGraph** class that handles the 
requests to Facebooks API that extends an extensible logger, **LogObject**.  
I have thought about moving the logger to another project but I was in a hurry 
to get an initial version cranked out.  You can find these log classes in the 
**log** subdirectory.

All fbGraph object requests are cached by a request id (which is basically the 
object id and an optional connection string).  You can find the cache classes 
in the **cache** subdirectory.  Currently static caching is included but it was
made to be extended to persistent caching mechanisms.

The **FbGraph** class is responsible for parsing the objects returned from 
Facebook into their respective client side classes which are defined in the 
**object** subdirectory.  Al objects directly or indirectly extend the 
**FbBase** class, which allows objects to make Facebook requests through a 
reference to the **FbGraph** instance that spawned the parent object.  This 
allows for an organic tree based request architecture.

For example, suppose we are requesting a Facebook user object (that we have 
access to).  This library can automatically pull objects that contain pages that
reference, for instance, graduation year, or employer, or school, etc..  This is
all done automatically behind the scenes so we end up with a fuller set of data
about the user without the extra work. And we don't have to worry about making 
redundant requests if multiple users share the same school.


Currently supported object types include:

**FbUser** - Facebook users
**FbGroup** - Facebook groups
**FbEvent** - Facebook events
**FbPage** - Facebook pages
**FbLink** - Facebook links
**FbPost** - Facebook posts (don't see many of these)
**FbNote** - Facebook notes (don't see many of these)
**FbStatus** - Facebook status messages
**FbAlbum** - Facebook photo albums
**FbPhoto** - Facebook photos
**FbVideo** - Facebook videos
**FbComment** - Facebook comments

As I have said above, properties may be different at Facebook.  I have 
incomplete information.  I hope this will change soon.


Initialization
--------------

Below are simple examples of how to use this library.

Initialize fbGraph:

    <?php    
    require 'PATH_TO_FBGRAPH/FbGraph.php';

    $graph = new FbGraph();    
    $graph->requireFacebook($PATH_TO_FACEBOOK_SDK);
    

Then public access:

    $graph->init($YOUR_APP_ID, $YOUR_API_SECRET);
    
    
Or existing Facebook instance (possibly logged in):

    $fb = new Facebook(array(
      'appId'  => $YOUR_APP_ID,
      'secret' => $YOUR_API_SECRET,
      'cookie' => TRUE, // Enable optional cookie support.
    ));
    
    // Authenticate...
    
    $graph->setFacebook($fb);


Basic Usage
-----------
    
Object interface:
    
    $graph->setDepth(1);  // No interior objects requested.
    
    $object          = $graph->request($id);
    $related_objects = $graph->request($id, $connection);
    
    // All objects share these properties.
    $id          = $object->getId();
    $name        = $object->getName();
    $type        = $object->getType();
    $connections = $object->getConnections(); 
    
    // Depending upon the type of object returned, this object should have
    // other properties as well.  See appropriate class.


Search interface:    
    
    $graph->setDepth(2);  // Recurse two levels deep.
    
    $types = $graph->getSearchTypes(); // Returns all available search types.
    $results = $graph->search('SEARCH_QUERY', $types['SEARCH_TYPE']);
    
    $object = $results[0]; 


TODO
----

1. Implement Facebook login / permissions?  Currently it supports public 
requests or requests via an established Facebook instance (that could be logged
in).

2. Implement the ability to push data objects to Facebook through this library.

3. Write unit tests to cover various functionality.

4. Finalize an object hierarchy and object properties.

5. Extend this library to other languages, particularly Actionscript/Flex and 
Java.

6. Create examples and (as always) improve documentation.

7. Oh yeah, fix bugs...






