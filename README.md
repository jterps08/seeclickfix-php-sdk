#PHP SeeClickFix API

This is a PHP 5.3+ API wrapper for the [SeeClickFix API](http://dev.seeclickfix.com/)

The API comes with a cURL client (`SeeClickFix\Net\CurlClient`) to access the SeeClickFix API.  You can create your own client, it just has to implement `SeeClickFix\Net\ClientInterface`.

---

##The API

All methods that access the API can throw exceptions. If the API request fails for any reason other than an expired/missing access token an exception of type `\SeeClickFix\Core\ApiException` will be thrown.  If the API request fails because of an expired/missing access token an exception of type `\SeeClickFix\Core\ApiAuthException` will be thrown. You can use this to redirect to your authorization page.

##Authentication

- [Set up a client for use with SeeClickFix's API](mailto:daniel@seeclickfix.com)

- Create an Auth Object and pass in the information from the API

```php
$auth_config = array(
    'client_id'         => '',
    'client_secret'     => '',
    'redirect_uri'      => ''
);

$auth = new SeeClickFix\Auth( $auth_config );
```

- Then you have to get the user to authorize your app 

```php
$auth->authorize();
```

- This will redirect the user to the SeeClickFix authorization page. After authorization SeeClickFix will redirect the user to the url in `$auth_config['redirect_uri']` with a code that you will need to obtain an access token

```php
$_SESSION['seeclickfix_access_token'] = $auth->getAccessToken( $_GET['code'] );
```

- Then use the access token in your code

```php
$seeclickfix = new SeeClickFix\SeeClickFix;
$seeclickfix->setAccessToken( $_SESSION['seeclickfix_access_token'] );
$current_user = $seeclickfix->getCurrentUser();
```

##Basic Usage

```php
$seeclickfix = new SeeClickFix\SeeClickFix( $_SESSION['seeclickfix_access_token'] );
$user = $seeclickfix->getUser( $user_id );
$location = $seeclickfix->getLocation( 3001881 );
$current_user = $seeclickfix->getCurrentUser();
```

##Current User

The current user object will give you the currently logged in user

```php
$current_user = $seeclickfix->getCurrentUser();
```

With this object you can:

- obtain the user's feed, liked media, follow requests

```php
$feed = $current_user->getFeed();
```

You can also perform all the functions you could on a normal user

##Collections

When making a call to a method that returns more than one of something (e.g. getIssues(), searchUsers() ), a collection object will be returned.  Collections can be iterated, counted, and accessed like arrays.

```php
$user = $seeclickfix->getLocation( $id );
$issues = $user->getIssues();
foreach( $issues as $issue ) {
     ...
}
```

The collection object will sometimes have an identifier to the "next page" that can be used to obtain the next page of the collection.

To obtain the identifier for the next page you call `getNext()` on the collection object.

For example:

```php
$user = $seeclickfix->getLocation( $id );
$issues = $user->getIssues();
$next_page = $issues->getNext();
```

Example usage:

```php
<a href="user_media.php?max_id=<?php echo $next_page ?>">
```

##Searching

You can search for locations, media, tags, and users.

```php
$locations = $seeclickfix->searchLocations( $lat, $lng );
$media = $seeclickfix->searchIssues( $lat, $lng );
$users = $seeclickfix->searchUsers( 'username' );
```
