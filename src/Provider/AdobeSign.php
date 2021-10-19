<?php

namespace Postyou\AdobeSignBundle\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

/** @package Postyou\AdobeSignBundle\Provider */
class AdobeSign extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public $host = 'https://api.eu2.adobesign.com';

    public function getBaseAuthorizationUrl()
    {
        return $this->host.'/public/oauth';
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->host.'/oauth/token';
    }

    public function getBaseRefreshTokenUrl()
    {
        return $this->host.'/oauth/refresh';
    }

    public function getBaseRevokeTokenUrl()
    {
        return $this->host.'/oauth/revoke';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
    }

    protected function getDefaultScopes()
    {
        return isset($this->scope) ? $this->scope : [];
    }

    protected function getScopeSeparator()
    {
        return ' ';
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
    }
}
