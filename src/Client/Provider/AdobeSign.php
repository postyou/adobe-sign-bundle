<?php

declare(strict_types=1);

/*
 * @author  POSTYOU Digital- & Filmagentur
 * @license MIT
 */

namespace Postyou\AdobeSignBundle\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class AdobeSign extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public string $api_access_point;
    public string $web_access_point;
    public string $path_oauth;
    public string $path_refresh;
    public string $path_token;
    public string $path_revoke;

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

    public function getBaseAuthorizationUrl(): string
    {
        return "{$this->web_access_point}{$this->path_oauth}";
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        if ('refresh_token' === $params['grant_type']) {
            return "{$this->api_access_point}{$this->path_refresh}";
        }

        return "{$this->api_access_point}{$this->path_token}";
    }

    public function getBaseRevokeTokenUrl(): string
    {
        return "{$this->api_access_point}{$this->path_revoke}";
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): void {}

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
            $message = '';

            if (\is_array($data)) {
                foreach ($data as $k => $v) {
                    $message .= " {$k}: {$v};";
                }
            }

            throw new \Exception("{$statusCode} {$response->getReasonPhrase()}:$message");
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token): void {}
}
