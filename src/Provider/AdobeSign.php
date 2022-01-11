<?php

declare(strict_types=1);

/*
 * @author  POSTYOU Digital- & Filmagentur
 * @license MIT
 */

namespace Postyou\AdobeSignBundle\Provider;

use Exception;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

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
        if ('refresh_token' === $params['grant_type']) {
            return $this->host.'/oauth/refresh';
        }

        return $this->host.'/oauth/token';
    }

    public function getBaseRevokeTokenUrl()
    {
        return $this->host.'/oauth/revoke';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): void
    {
    }

    protected function getDefaultScopes()
    {
        return $this->scope ?? [];
    }

    protected function getScopeSeparator()
    {
        return ' ';
    }

    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if (200 !== $response->getStatusCode()) {
            throw new Exception("Error Processing Request", 1);
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token): void
    {
    }
}
