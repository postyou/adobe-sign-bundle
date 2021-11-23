<?php

declare(strict_types=1);

/*
 * @author  POSTYOU Digital- & Filmagentur
 * @license MIT
 */

namespace Postyou\AdobeSignBundle\Provider;

use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use Webmozart\PathUtil\Path;

class AccessTokenManager
{
    private string $configFile;
    private Filesystem $filesystem;
    private ?AccessToken $accessToken = null;

    public function __construct(string $projectDir)
    {
        if (false !== ($realpath = realpath($projectDir))) {
            $projectDir = (string) $realpath;
        }

        $this->configFile = Path::join($projectDir, 'config/adobe-sign.yaml');
        $this->filesystem = new Filesystem();
    }

    public function read(): AccessToken
    {
        $this->accessToken = null;

        if (is_file($this->configFile)) {
            $config = Yaml::parse(file_get_contents($this->configFile));

            if (\is_array($config)) {
                $this->accessToken = new AccessToken($config);
            }
        }

        return $this->accessToken;
    }

    public function write(AccessToken $accessToken): void
    {
        $this->accessToken = $accessToken;

        $this->filesystem->dumpFile($this->configFile, Yaml::dump($accessToken->jsonSerialize()));
    }
}
