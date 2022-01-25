<?php

declare(strict_types=1);

/*
 * @author  POSTYOU Digital- & Filmagentur
 * @license MIT
 */

namespace Postyou\AdobeSignBundle\Client;

use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;

class AdobeSignClient extends OAuth2Client
{
    /**
     * @throws IdentityProviderException
     *
     * @return mixed
     */
    public function createAgreement(AccessToken $accessToken, string $email, array $agreementInfo)
    {
        /** @var AdobeSignProvider */
        $provider = $this->getOAuth2Provider();
        $tokenValues = $accessToken->getValues();

        $apiAccessPoint = $tokenValues['api_access_point'] ?? $provider->api_access_point;

        $request = $provider->getAuthenticatedRequest(
            'POST',
            $apiAccessPoint.'api/rest/v6/agreements',
            $accessToken,
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-api-user' => "email:{$email}",
                ],
                'body' => json_encode($agreementInfo),
            ]
        );

        return $provider->getParsedResponse($request);
    }

    public function listUsers(AccessToken $accessToken)
    {
        $provider = $this->getOAuth2Provider();
        $tokenValues = $accessToken->getValues();

        $request = $provider->getAuthenticatedRequest(
            'GET',
            $tokenValues['api_access_point'].'api/rest/v6/users',
            $accessToken
        );

        return $provider->getParsedResponse($request);
    }

    /**
     * @throws IdentityProviderException
     *
     * @return mixed
     */
    public function listLibraryDocuments(AccessToken $accessToken)
    {
        $provider = $this->getOAuth2Provider();
        $tokenValues = $accessToken->getValues();

        $apiAccessPoint = $tokenValues['api_access_point'] ?? $provider->api_access_point;

        $request = $provider->getAuthenticatedRequest(
            'GET',
            $apiAccessPoint.'api/rest/v6/libraryDocuments',
            $accessToken
        );

        return $provider->getParsedResponse($request);
    }
}
