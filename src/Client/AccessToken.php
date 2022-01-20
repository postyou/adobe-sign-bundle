<?php

declare(strict_types=1);

/*
 * @author  POSTYOU Digital- & Filmagentur
 * @license MIT
 */

namespace Postyou\AdobeSignBundle\Client;

use League\OAuth2\Client\Token\AccessToken as LeagueAccessToken;

class AccessToken extends LeagueAccessToken
{
    protected string $api_access_point;
    protected string $web_access_point;

    public function __construct(array $options = [])
    {
        parent::__construct($options);

        if (!empty($options['api_access_point'])) {
            $this->api_access_point = $options['api_access_point'];
        }

        if (!empty($options['web_access_point'])) {
            $this->web_access_point = $options['web_access_point'];
        }
    }

    public function getApiAccessPoint()
    {
        return $this->api_access_point;
    }

    public function getWebAccessPoint()
    {
        return $this->web_access_point;
    }
}
