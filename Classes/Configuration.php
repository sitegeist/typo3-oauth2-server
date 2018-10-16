<?php
declare(strict_types = 1);

namespace FGTCLB\OAuth2Server;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Configuration for OAuth2 processing
 */
final class Configuration
{
    /**
     * @var Path to a private RSA key
     */
    protected $privateKeyFile;

    /**
     * @var Path to a public RSA key
     */
    protected $publicKeyFile;

    /**
     * @param ExtensionConfiguration|null $extensionConfiguration
     * @throws \InvalidArgumentException if the extension configuration is invalid/incomplete
     */
    public function __construct(ExtensionConfiguration $extensionConfiguration = null)
    {
        $configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('oauth2_server');

        if (empty($configuration['privateKeyFile'])) {
            throw new \InvalidArgumentException('Missing "privateKeyFile" in OAuth2 server extension configuration', 1539686145);
        }

        if (empty($configuration['publicKeyFile'])) {
            throw new \InvalidArgumentException('Missing "publicKeyFile" in OAuth2 server extension configuration', 1539686197);
        }

        $this->privateKeyFile = $configuration['privateKeyFile'];
        $this->publicKeyFile = $configuration['publicKeyFile'];
    }

    /**
     * Get the path to the private RSA key
     *
     * @return string
     */
    public function getPrivateKeyFile(): string
    {
        return $this->privateKeyFile;
    }

    /**
     * Get the path to the public RSA key
     *
     * @return string
     */
    public function getPublicKeyFile(): string
    {
        return $this->publicKeyFile;
    }
}
