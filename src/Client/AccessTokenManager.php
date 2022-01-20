<?php

declare(strict_types=1);

/*
 * @author  POSTYOU Digital- & Filmagentur
 * @license MIT
 */

namespace Postyou\AdobeSignBundle\Client;

use Exception;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Yaml\Yaml;

class AccessTokenManager
{
    private string $configFile;
    private Filesystem $filesystem;
    private ?AdobeAccessToken $accessToken = null;
    private AdobeSignClient $client;

    public function __construct(ClientRegistry $clientRegistry, string $projectDir)
    {
        if (false !== ($realpath = realpath($projectDir))) {
            $projectDir = (string) $realpath;
        }

        $this->configFile = Path::join($projectDir, 'config/adobe-sign.yaml');
        $this->filesystem = new Filesystem();
        $this->accessToken = $this->read();
        $this->client = $clientRegistry->getClient('adobe_sign');
    }

    public function getAccessToken(): AdobeAccessToken
    {
        if (empty($this->accessToken)) {
            throw new Exception('No Access Token available', 1);
        }

        if ($this->accessToken->hasExpired()) {
            $refreshToken = $this->accessToken->getRefreshToken();
            $accessToken = $this->client->refreshAccessToken($refreshToken);

            $this->write($accessToken, $refreshToken);
        }

        return $this->accessToken;
    }

    public function write(AdobeAccessToken $accessToken): void
    {
        $content = $accessToken->jsonSerialize();

        if (isset($this->accessToken)) {
            $content = array_merge($this->accessToken->jsonSerialize(), $content);
        }

        $this->filesystem->dumpFile($this->configFile, Yaml::dump($content));
    }

    protected function read(): ?AdobeAccessToken
    {
        $accessToken = null;

        if (is_file($this->configFile)) {
            $config = Yaml::parse(file_get_contents($this->configFile));

            if (\is_array($config)) {
                $accessToken = new AdobeAccessToken($config);
            }
        }

        return $accessToken;
    }
}
