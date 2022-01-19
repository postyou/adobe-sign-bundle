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
        $request = $provider->getAuthenticatedRequest(
            'POST',
            // todo: url
            $provider->host.'/api/rest/v6/agreements',
            $accessToken,
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-on-behalf-of-user' => $email,
                ],
                'body' => json_encode($agreementInfo),
            ]
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
        $request = $provider->getAuthenticatedRequest(
            'GET',
            // todo: url
            $provider->host.'/api/rest/v6/libraryDocuments',
            $accessToken
        );

        return $provider->getParsedResponse($request);
    }
}
