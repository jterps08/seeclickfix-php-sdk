#PHP SeeClickFix API

This is a PHP 5.3+ API wrapper for the [SeeClickFix API](http://dev.seeclickfix.com/)

The API comes with a cURL client (`SeeClickFix\Net\CurlClient`) to access the SeeClickFix API.  You can create your own client, it just has to implement `SeeClickFix\Net\ClientInterface`.

---
 
- [The API](#the-api)
- [Authentication](#authentication)
- [API Calls](#api-calls)
 - [Users](#usage)
 - [Current User](#basic-usage)
 - [Current User](#basic-usage)
 - [Current User](#basic-usage)
 - [Current User](#basic-usage)
 - [Current User](#basic-usage)
- [Collections](#collections)
- [Searching](#searching)

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

$seeclickfix = new SeeClickFixSDK\SeeClickFix( $auth_config );
```

- Then you have to get the user to authorize your app 

```php
$url = $seeclickfix->getAuthorizationUri();
header("Location:$url");
exit;
```

- This will redirect the user to the SeeClickFix authorization page. After authorization SeeClickFix will redirect the user to the url in `$auth_config['redirect_uri']` with a code that you will need to obtain an access token

```php
$_SESSION['seeclickfix_access_token'] = $seeclickfix->getAccessToken( $_GET['code'] );
```

- Then use the access token in your code

```php
$seeclickfix = new SeeClickFixSDK\SeeClickFix;
$seeclickfix->setAccessToken( $_SESSION['seeclickfix_access_token'] );
$current_user = $seeclickfix->getCurrentUser();
```

##API Calls

This is a quick referance of the resources that make up the official SeeClickFix API v2. [Full API Doc](http://dev.seeclickfix.com).


###Places List

Returns a list of places closest to `point`. [View API Doc](http://dev.seeclickfix.com/v2/places/#list-places)

```php
$places = SeeClickFix::getPlaces([
    "lat" => 41.29841599999985,
    "lng" => -72.9291785
])
```

###Single Place

Returns a single place by `id`. [View API Doc](http://dev.seeclickfix.com/v2/places/#show-place)

```php
$place = SeeClickFix::getPlace( 3039 )
```

###Issues List

Returns a list of issues within a place. [View API Doc](http://dev.seeclickfix.com/v2/issues/#list-issues)

```php
$issues = SeeClickFix::getIssues([
    'place_url' => 'new-haven'
]);
```

###Single Issue

Returns a single issue by id. [View API Doc](http://dev.seeclickfix.com/v2/issues/#get-a-single-issue)

```php
$issue = SeeClickFix::getIssue(504561)
```

###Users

Returns a list of users closest to `point`. [View API Doc](http://dev.seeclickfix.com/v2/users/#list-users)

```php
$users = SeeClickFix::getUsers([
    "lat" => 41.29841599999985,
    "lng" => -72.9291785
]);
```

###Single User

Returns a single user by `id`. [View API Doc](http://dev.seeclickfix.com/v2/users/#show-user-by-id)

```php
$user = SeeClickFix::getUser( 100 );
```

###Current User

The current user object will give you the currently logged in user. [View API Doc](http://dev.seeclickfix.com/v2/users/#show-current-user)

```php
$current_user = $seeclickfix->getCurrentUser();
```

##Collections

When making a call to a method that returns more than one of something (e.g. getIssues(), searchUsers() ), a collection object will be returned.  Collections can be iterated, counted, and accessed like arrays.

```php
$place = $seeclickfix->getPlace( $id );
$issues = $place->getIssues();
foreach( $issues as $issue ) {
     ...
}
```

The collection object will sometimes have an identifier to the "next page" that can be used to obtain the next page of the collection.

To obtain the identifier for the next page you call `getNext()` on the collection object.

For example:

```php
$user = $seeclickfix->getPlace( $id );
$issues = $user->getIssues();
$next_page = $issues->getNext();
```

Example usage:

```php
<a href="user_media.php?max_id=<?php echo $next_page ?>">
```

##Searching

You can search for places and users.

```php
$places = $seeclickfix->searchPlaces( $lat, $lng );
$issues = $seeclickfix->searchIssues( $lat, $lng );
$users = $seeclickfix->searchUsers( 'username' );
```
