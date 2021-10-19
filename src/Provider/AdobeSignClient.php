<?php

namespace Postyou\AdobeSignBundle\Provider;

use Contao\System;
use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;

class AdobeSignClient extends OAuth2Client
{
    public function createAgreement(AccessToken $accessToken, array $agreementInfo)
    {
        /** @var AdobeSign */
        $provider = $this->getOAuth2Provider();
        $request = $provider->getAuthenticatedRequest(
            'POST',
            // todo: url
            $provider->host.'/api/rest/v6/agreements',
            $accessToken,
            [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($agreementInfo)
            ]
        );

        return $provider->getParsedResponse($request);
    }

    /**
     * @param AccessToken $accessToken
     * @return mixed
     * @throws IdentityProviderException
     */
    public function listLibraryDocuments(AccessToken $accessToken)
    {
        $provider = $this->getOAuth2Provider();
        $request = $provider->getAuthenticatedRequest(
            'GET',
            // todo: url
            $provider->host.'/api/rest/v6/libraryDocuments',
            $accessToken
        );

        return $provider->getParsedResponse($request);
    }
}
