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
    protected string $path_oauth;
    protected string $path_refresh;
    protected string $path_token;
    protected string $path_revoke;

    public function __construct(array $options = [], array $collaborators = [])
    {
        parent::__construct($options, $collaborators);

        $this->api_access_point = $options['api_access_point'];

        $this->web_access_point = $options['web_access_point'];

        $this->path_oauth = $options['path_oauth'];

        $this->path_refresh = $options['path_refresh'];

        $this->path_token = $options['path_token'];

        $this->path_revoke = $options['path_revoke'];
    }

    public function getBaseAuthorizationUrl()
    {
        dd($this->web_access_point);
        return "{$this->web_access_point}{$this->path_oauth}";
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        if ('refresh_token' === $params['grant_type']) {
            return "{$this->api_access_point}{$this->path_refresh}";
        }

        return "{$this->api_access_point}{$this->path_token}";
    }

    public function getBaseRevokeTokenUrl()
    {
        return "{$this->api_access_point}{$this->path_revoke}";
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
