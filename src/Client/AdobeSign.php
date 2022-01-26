<?php

declare(strict_types=1);

/*
 * @author  POSTYOU Digital- & Filmagentur
 * @license MIT
 */

namespace Postyou\AdobeSignBundle\Client;

use League\OAuth2\Client\Token\AccessToken;
use Postyou\AdobeSignBundle\Client\Provider\AdobeSign as AdobeSignProvider;

class AdobeSign
{
    protected AdobeSignProvider $provider;

    protected AccessToken $accessToken;

    protected string $apiAccessPoint;

    public function __construct(AccessTokenManager $accessTokenManager)
    {
        $this->accessToken = $accessTokenManager->getAccessToken();
        $this->provider = $accessTokenManager->getProvider();

        $tokenValues = $this->accessToken->getValues();
        $this->apiAccessPoint = $tokenValues['api_access_point'] ?? $this->provider->api_access_point;
    }

    public function createAgreement(array $agreementInfo, array $headers = []): mixed
    {
        $request = $this->provider->getAuthenticatedRequest(
            'POST',
            "{$this->apiAccessPoint}api/rest/v6/agreements",
            $this->accessToken,
            [
                'headers' => array_merge([
                    'Content-Type' => 'application/json',
                ], $headers),
                'body' => json_encode($agreementInfo),
            ]
        );

        return $this->provider->getParsedResponse($request);
    }

    public function listUsers(): mixed
    {
        $request = $this->provider->getAuthenticatedRequest(
            'GET',
            "{$this->apiAccessPoint}api/rest/v6/users",
            $this->accessToken
        );

        return $this->provider->getParsedResponse($request);
    }

    public function listLibraryDocuments(): mixed
    {
        $request = $this->provider->getAuthenticatedRequest(
            'GET',
            "{$this->apiAccessPoint}api/rest/v6/libraryDocuments",
            $this->accessToken
        );

        return $this->provider->getParsedResponse($request);
    }
}
