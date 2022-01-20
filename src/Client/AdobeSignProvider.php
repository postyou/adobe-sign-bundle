<?php

declare(strict_types=1);

/*
 * @author  POSTYOU Digital- & Filmagentur
 * @license MIT
 */

namespace Postyou\AdobeSignBundle\Client;

use Exception;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class AdobeSignProvider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    protected string $api_access_point;
    protected string $web_access_point;

    public function getBaseAuthorizationUrl()
    {
        return $this->web_access_point.'public/oauth/v2';
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        if ('refresh_token' === $params['grant_type']) {
            return $this->api_access_point.'oauth/v2/refresh';
        }

        return $this->api_access_point.'oauth/v2/token';
    }

    public function getBaseRevokeTokenUrl()
    {
        return $this->api_access_point.'oauth/v2/revoke';
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
        $statusCode = $response->getStatusCode();
        if (400 <= $statusCode) {
            throw new Exception($statusCode.' '.$data['code'].': '.$data['message'], 1);
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token): void
    {
    }
}
