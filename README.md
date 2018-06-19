# Ataama Provider for OAuth 2.0 Client

[Ataama](http://www.ataama.com/) OAuth 2.0 support for the PHP League’s [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

```
$ composer require knightar/oauth2-ataama
```

## Usage

Valid Sites:
* AWOL Academy: awol
* Global Affiliate Zone: gaz

```php
$provider = new SeinopSys\OAuth2\Client\Provider\AtaamaProvider([
	'clientId' => 'client_id',
	'clientSecret' => 'client_secret',
	'redirectUri' => 'http://example.com/auth',
	'site' => 'site'
]);

$accessToken = $provider->getAccessToken('authorization_code', [
	'code' => $_GET['code'],
	'scope' => ['user','browse'] // optional, defaults to ['user']
]);
$actualToken = $accessToken->getToken();
$refreshToken = $accessToken->getRefresh();

// Once it expires

$newAccessToken = $provider->getAccessToken('refresh_token', [
	'refresh_token' => $refreshToken
]);
```
