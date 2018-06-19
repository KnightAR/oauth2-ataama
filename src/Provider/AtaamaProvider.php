<?php

namespace Ataama\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class AtaamaProvider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public $host = 'https://%s.api.ataama.com';
    public $site = 'site';

    public function getBaseSite()
    {
        return sprintf($this->host, $this->site);
    }

    public function getBaseAuthorizationUrl()
    {
        return $this->getBaseSite() . '/oauth2/authorize';
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->getBaseSite() . '/oauth2/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->getBaseSite() . '/v1/user/whoami';
    }

    protected function getDefaultScopes()
    {
        return ['user'];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (isset($data['error'])) {
            throw new IdentityProviderException(
                $data['error'] ?: $response->getReasonPhrase(),
                $response->getStatusCode(),
                $response->getBody()
            );
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new AtaamaResourceOwner($response);
    }

    protected function getScopeSeparator()
    {
        return ' ';
    }

    /**
     * @inheritdoc
     * @throws IdentityProviderException
     */
    protected function parseResponse(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode >= 500) {
            throw new IdentityProviderException(
                'The OAuth server returned an unexpected response',
                $statusCode,
                $response->getBody()
            );
        } else if (preg_match('#^text/html#i', join(' ', $response->getHeader('Content-Type')))) {
            throw new IdentityProviderException(
                'The OAuth server returned an unexpected response',
                $statusCode,
                $response->getBody()
            );
        }
        return parent::parseResponse($response);
    }
}
