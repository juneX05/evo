<?php
/*
 * This file is part of the Evo package.
 *
 * (c) John Andrew <simplygenius78@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Evo\Session;

////use JetBrains\PhpStorm\Pure;

/**
 * SessionEnvironment handles the session configuration from the application
 * which passes in the user define session options. This class also exposes
 * session helper methods for fetching name, path, etc...
 */
class SessionEnvironment
{
    protected const SESSION_VERSION = '1.0.0';
    protected array $sessionConfig;

    public function __construct(array $sessionConfig)
    {
        if (count($sessionConfig) < 0 || !is_array($sessionConfig)) {
            throw new \LogicException('Session environment has failed to load. Ensure your are passing the correct yaml configuration file to the session facade class object');
        }
        $this->sessionConfig = $sessionConfig;
    }

    /**
     * Returns the complete session configuration array
     */
    public function getConfig(): array
    {
        return $this->sessionConfig;
    }

    /**
     * The lifetime of the cookie in seconds.
     */
    public function getLifetime(): int
    {
        $lifetime = (isset($this->getConfig()['lifetime']) ? filter_var($this->getConfig()['lifetime'], FILTER_VALIDATE_INT) : 120);
        if ($lifetime) {
            return $lifetime;
        }
    }

    /**
     * Path on the domain where the cookie will work. Use a single slash ('/') 
     * for all paths on the domain.
     */
    public function getPath(): string
    {
        $path = ($this->getConfig()['path'] ?? '');
        if ($path) {
            return $path;
        }
    }

    /**
     * Cookie domain, for example 'www.php.net'. To make cookies visible on all
     * subdomains then the domain must be prefixed with a dot like '.php.net'.
     */
    public function getDomain(): ?string
    {
        $domain = ($this->getConfig()['domain'] ?? $_SERVER['SERVER_NAME']);
        if ($domain) {
            return $domain;
        }
    }

    /**
     * If TRUE cookie will only be sent over secure connections.
     */
    public function isSecure(): bool
    {
        return ($this->getConfig()['secure'] ?? isset($_SERVER['HTTPS']));
    }

    /**
     * If set to TRUE then PHP will attempt to send the httponly flag when 
     * setting the session cookie.
     */
    public function isHttpOnly(): ?bool
    {
        return ($this->getConfig()['httpOnly'] ?? NULL);
    }

    /**
     * Get the unique session identifier
     */
    public function getSessionName(): string
    {
        $sessionName = ($this->getConfig()['session_name'] ?? '');
        if ($sessionName) {
            return $sessionName;
        }
    }

    /**
     * PHP session runtime configuration strings
     */
    public function getSessionRuntimeConfigurations(): array
    {
        return array('session.gc_maxlifetime', 'session.gc_divisor', 'session.gc_probability', 'session.lifetime', 'session.use_cookies');
    }

    /**
     * Get the session runtime configuration values from the session environment
     * object. As the array is index with the 'session.' prefix we must handle this
     * by removing the prefix. In order to match the configuration values. 
     * Values are fetched using the getConfig() method and simple calling the
     * config value within the square brackets.
     */
    public function getSessionIniValues()
    {
        foreach ($this->getSessionRuntimeConfigurations() as $runtimeConfig) {
            switch ($runtimeConfig) {
                case $runtimeConfig:
                    return $this->getConfig()[str_replace('session.', '', $runtimeConfig)];
                    break;
            }
        }
    }
}
