<?php

declare(strict_types=1);

/*
 * @author  POSTYOU Digital- & Filmagentur
 * @license MIT
 */

namespace Postyou\AdobeSignBundle\Client;

use Exception;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use League\OAuth2\Client\Token\AccessToken;
use Postyou\AdobeSignBundle\Client\Provider\AdobeSign as AdobeSignProvider;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Yaml\Yaml;

class AccessTokenManager
{
    public OAuth2Client $client;
    private string $configFile;

    private Filesystem $filesystem;

    private ?AccessToken $accessToken = null;

    public function __construct(ClientRegistry $clientRegistry, string $projectDir)
    {
        if (false !== ($realpath = realpath($projectDir))) {
            $projectDir = $realpath;
        }

        $this->configFile = Path::join($projectDir, 'config/adobe-sign.yaml');
        $this->filesystem = new Filesystem();
        $this->accessToken = $this->read();
        $this->client = $clientRegistry->getClient('adobe_sign');
    }

    public function getAccessToken(): AccessToken
    {
        if (empty($this->accessToken)) {
            throw new Exception('No Access Token available', 1);
        }

        if ($this->accessToken->hasExpired()) {
            $refreshToken = $this->accessToken->getRefreshToken();

            if (!isset($refreshToken)) {
                throw new Exception('No Refresh Token available', 1);
            }

            /** @var AccessToken $accessToken */
            $accessToken = $this->client->refreshAccessToken($refreshToken);

            return $this->write($accessToken);
        }

        return $this->accessToken;
    }

    public function getProvider(): AdobeSignProvider
    {
        return $this->client->getOAuth2Provider();
    }

    public function write(AccessToken $accessToken): AccessToken
    {
        $content = $accessToken->jsonSerialize();

        if (isset($this->accessToken)) {
            $content = array_merge($this->accessToken->jsonSerialize(), $content);
        }

        $this->filesystem->dumpFile($this->configFile, Yaml::dump($content));
        $this->accessToken = $this->read();

        if (!isset($this->accessToken)) {
            throw new Exception('Error writing access token', 1);
        }

        return $this->accessToken;
    }

    protected function read(): ?AccessToken
    {
        $accessToken = null;

        if (is_file($this->configFile)) {
            $config = Yaml::parse(file_get_contents($this->configFile));

            if (\is_array($config)) {
                $accessToken = new AccessToken($config);
            }
        }

        return $accessToken;
    }
}
