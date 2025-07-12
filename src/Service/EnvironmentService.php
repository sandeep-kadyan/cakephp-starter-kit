<?php
declare(strict_types=1);

namespace App\Service;

use Psr\Http\Message\ServerRequestInterface;

/**
 * EnvironmentService provides access to request environment variables with defaults.
 *
 * Usage:
 * $service = new EnvironmentService();
 * $service->setServerName(); // Uses current request
 * $serverName = $service->getServerName();
 */
class EnvironmentService
{
    /**
     * The current request instance.
     *
     * @var \Psr\Http\Message\ServerRequestInterface|null
     */
    protected ?ServerRequestInterface $request = null;

    /**
     * The current environment values for this instance.
     *
     * @var array<string, mixed>
     */
    protected array $_environment = [];

    /**
     * Default environment values.
     *
     * @var array<string, mixed>
     */
    protected static array $defaultEnvironment = [
        'DOCUMENT_ROOT' => '',
        'REMOTE_ADDR' => '',
        'REMOTE_PORT' => '',
        'SERVER_SOFTWARE' => '',
        'SERVER_PROTOCOL' => '',
        'SERVER_NAME' => '',
        'SERVER_PORT' => '',
        'REQUEST_URI' => '',
        'REQUEST_METHOD' => '',
        'SCRIPT_NAME' => '',
        'SCRIPT_FILENAME' => '',
        'PATH_INFO' => '',
        'PHP_SELF' => '',
        'HTTP_HOST' => '',
        'HTTP_CONNECTION' => '',
        'HTTP_CACHE_CONTROL' => '',
        'HTTP_SEC_CH_UA' => '',
        'HTTP_SEC_CH_UA_MOBILE' => '',
        'HTTP_SEC_CH_UA_PLATFORM' => '',
        'HTTP_UPGRADE_INSECURE_REQUESTS' => '',
        'HTTP_USER_AGENT' => '',
        'HTTP_ACCEPT' => '',
        'HTTP_SEC_FETCH_SITE' => '',
        'HTTP_SEC_FETCH_MODE' => '',
        'HTTP_SEC_FETCH_USER' => '',
        'HTTP_SEC_FETCH_DEST' => '',
        'HTTP_REFERER' => '',
        'HTTP_ACCEPT_ENCODING' => '',
        'HTTP_ACCEPT_LANGUAGE' => '',
        'HTTP_COOKIE' => '',
        'REQUEST_TIME_FLOAT' => 0.0,
        'REQUEST_TIME' => 0,
        'ORIGINAL_REQUEST_METHOD' => '',
        'HTTPS' => '',
    ];

    /**
     * Initialize the service with the current request.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The current request.
     * @return void
     */
    public function __construct(?ServerRequestInterface $request = null)
    {
        $this->request = $request;
    }

    /**
     * Get an environment value by key.
     *
     * @param string $key The environment key.
     * @return mixed The value or the default if not set.
     */
    public function getEnv(string $key): mixed
    {
        if (array_key_exists($key, $this->_environment)) {
            return $this->_environment[$key];
        }
        if ($this->request) {
            $value = $this->request->getServerParams()[$key] ?? null;
            if ($value !== null) {
                return $value;
            }
        }

        return static::$defaultEnvironment[$key] ?? null;
    }

    /**
     * Set an environment value by key.
     * If no value is provided, use the value from the current request if available.
     *
     * @param string $key The environment key.
     * @param mixed|null $value The value to set, or null to use current request.
     * @return void
     */
    public function setEnv(string $key, mixed $value = null): void
    {
        if ($value === null && $this->request) {
            $value = $this->request->getServerParams()[$key] ?? static::$defaultEnvironment[$key] ?? null;
        }
        $this->_environment[$key] = $value;
    }

    /**
     * Get DOCUMENT_ROOT value.
     *
     * @return string DOCUMENT_ROOT value or default.
     */
    public function getDocumentRoot(): string
    {
        return $this->getEnv('DOCUMENT_ROOT');
    }

    /**
     * Set DOCUMENT_ROOT value.
     *
     * @param string|null $value DOCUMENT_ROOT value to set, or null to use current request.
     * @return void
     */
    public function setDocumentRoot(?string $value = null): void
    {
        $this->setEnv('DOCUMENT_ROOT', $value);
    }

    /**
     * Get REMOTE_ADDR value.
     *
     * @return string REMOTE_ADDR value or default.
     */
    public function getRemoteAddr(): string
    {
        return $this->getEnv('REMOTE_ADDR');
    }

    /**
     * Set REMOTE_ADDR value.
     *
     * @param string|null $value REMOTE_ADDR value to set, or null to use current request.
     * @return void
     */
    public function setRemoteAddr(?string $value = null): void
    {
        $this->setEnv('REMOTE_ADDR', $value);
    }

    /**
     * Get REMOTE_PORT value.
     *
     * @return string REMOTE_PORT value or default.
     */
    public function getRemotePort(): string
    {
        return $this->getEnv('REMOTE_PORT');
    }

    /**
     * Set REMOTE_PORT value.
     *
     * @param string|null $value REMOTE_PORT value to set, or null to use current request.
     * @return void
     */
    public function setRemotePort(?string $value = null): void
    {
        $this->setEnv('REMOTE_PORT', $value);
    }

    /**
     * Get SERVER_SOFTWARE value.
     *
     * @return string SERVER_SOFTWARE value or default.
     */
    public function getServerSoftware(): string
    {
        return $this->getEnv('SERVER_SOFTWARE');
    }

    /**
     * Set SERVER_SOFTWARE value.
     *
     * @param string|null $value SERVER_SOFTWARE value to set, or null to use current request.
     * @return void
     */
    public function setServerSoftware(?string $value = null): void
    {
        $this->setEnv('SERVER_SOFTWARE', $value);
    }

    /**
     * Get SERVER_PROTOCOL value.
     *
     * @return string SERVER_PROTOCOL value or default.
     */
    public function getServerProtocol(): string
    {
        return $this->getEnv('SERVER_PROTOCOL');
    }

    /**
     * Set SERVER_PROTOCOL value.
     *
     * @param string|null $value SERVER_PROTOCOL value to set, or null to use current request.
     * @return void
     */
    public function setServerProtocol(?string $value = null): void
    {
        $this->setEnv('SERVER_PROTOCOL', $value);
    }

    /**
     * Get SERVER_NAME value.
     *
     * @return string SERVER_NAME value or default.
     */
    public function getServerName(): string
    {
        return $this->getEnv('SERVER_NAME');
    }

    /**
     * Set SERVER_NAME value.
     *
     * @param string|null $value SERVER_NAME value to set, or null to use current request.
     * @return void
     */
    public function setServerName(?string $value = null): void
    {
        $this->setEnv('SERVER_NAME', $value);
    }

    /**
     * Get SERVER_PORT value.
     *
     * @return string SERVER_PORT value or default.
     */
    public function getServerPort(): string
    {
        return $this->getEnv('SERVER_PORT');
    }

    /**
     * Set SERVER_PORT value.
     *
     * @param string|null $value SERVER_PORT value to set, or null to use current request.
     * @return void
     */
    public function setServerPort(?string $value = null): void
    {
        $this->setEnv('SERVER_PORT', $value);
    }

    /**
     * Get REQUEST_URI value.
     *
     * @return string REQUEST_URI value or default.
     */
    public function getRequestUri(): string
    {
        return $this->getEnv('REQUEST_URI');
    }

    /**
     * Set REQUEST_URI value.
     *
     * @param string|null $value REQUEST_URI value to set, or null to use current request.
     * @return void
     */
    public function setRequestUri(?string $value = null): void
    {
        $this->setEnv('REQUEST_URI', $value);
    }

    /**
     * Get REQUEST_METHOD value.
     *
     * @return string REQUEST_METHOD value or default.
     */
    public function getRequestMethod(): string
    {
        return $this->getEnv('REQUEST_METHOD');
    }

    /**
     * Set REQUEST_METHOD value.
     *
     * @param string|null $value REQUEST_METHOD value to set, or null to use current request.
     * @return void
     */
    public function setRequestMethod(?string $value = null): void
    {
        $this->setEnv('REQUEST_METHOD', $value);
    }

    /**
     * Get SCRIPT_NAME value.
     *
     * @return string SCRIPT_NAME value or default.
     */
    public function getScriptName(): string
    {
        return $this->getEnv('SCRIPT_NAME');
    }

    /**
     * Set SCRIPT_NAME value.
     *
     * @param string|null $value SCRIPT_NAME value to set, or null to use current request.
     * @return void
     */
    public function setScriptName(?string $value = null): void
    {
        $this->setEnv('SCRIPT_NAME', $value);
    }

    /**
     * Get SCRIPT_FILENAME value.
     *
     * @return string SCRIPT_FILENAME value or default.
     */
    public function getScriptFilename(): string
    {
        return $this->getEnv('SCRIPT_FILENAME');
    }

    /**
     * Set SCRIPT_FILENAME value.
     *
     * @param string|null $value SCRIPT_FILENAME value to set, or null to use current request.
     * @return void
     */
    public function setScriptFilename(?string $value = null): void
    {
        $this->setEnv('SCRIPT_FILENAME', $value);
    }

    /**
     * Get PATH_INFO value.
     *
     * @return string PATH_INFO value or default.
     */
    public function getPathInfo(): string
    {
        return $this->getEnv('PATH_INFO');
    }

    /**
     * Set PATH_INFO value.
     *
     * @param string|null $value PATH_INFO value to set, or null to use current request.
     * @return void
     */
    public function setPathInfo(?string $value = null): void
    {
        $this->setEnv('PATH_INFO', $value);
    }

    /**
     * Get PHP_SELF value.
     *
     * @return string PHP_SELF value or default.
     */
    public function getPhpSelf(): string
    {
        return $this->getEnv('PHP_SELF');
    }

    /**
     * Set PHP_SELF value.
     *
     * @param string|null $value PHP_SELF value to set, or null to use current request.
     * @return void
     */
    public function setPhpSelf(?string $value = null): void
    {
        $this->setEnv('PHP_SELF', $value);
    }

    /**
     * Get HTTP_HOST value.
     *
     * @return string HTTP_HOST value or default.
     */
    public function getHttpHost(): string
    {
        return $this->getEnv('HTTP_HOST');
    }

    /**
     * Set HTTP_HOST value.
     *
     * @param string|null $value HTTP_HOST value to set, or null to use current request.
     * @return void
     */
    public function setHttpHost(?string $value = null): void
    {
        $this->setEnv('HTTP_HOST', $value);
    }

    /**
     * Get HTTP_CONNECTION value.
     *
     * @return string HTTP_CONNECTION value or default.
     */
    public function getHttpConnection(): string
    {
        return $this->getEnv('HTTP_CONNECTION');
    }

    /**
     * Set HTTP_CONNECTION value.
     *
     * @param string|null $value HTTP_CONNECTION value to set, or null to use current request.
     * @return void
     */
    public function setHttpConnection(?string $value = null): void
    {
        $this->setEnv('HTTP_CONNECTION', $value);
    }

    /**
     * Get HTTP_CACHE_CONTROL value.
     *
     * @return string HTTP_CACHE_CONTROL value or default.
     */
    public function getHttpCacheControl(): string
    {
        return $this->getEnv('HTTP_CACHE_CONTROL');
    }

    /**
     * Set HTTP_CACHE_CONTROL value.
     *
     * @param string|null $value HTTP_CACHE_CONTROL value to set, or null to use current request.
     * @return void
     */
    public function setHttpCacheControl(?string $value = null): void
    {
        $this->setEnv('HTTP_CACHE_CONTROL', $value);
    }

    /**
     * Get HTTP_SEC_CH_UA value.
     *
     * @return string HTTP_SEC_CH_UA value or default.
     */
    public function getHttpSecChUa(): string
    {
        return $this->getEnv('HTTP_SEC_CH_UA');
    }

    /**
     * Set HTTP_SEC_CH_UA value.
     *
     * @param string|null $value HTTP_SEC_CH_UA value to set, or null to use current request.
     * @return void
     */
    public function setHttpSecChUa(?string $value = null): void
    {
        $this->setEnv('HTTP_SEC_CH_UA', $value);
    }

    /**
     * Get HTTP_SEC_CH_UA_MOBILE value.
     *
     * @return string HTTP_SEC_CH_UA_MOBILE value or default.
     */
    public function getHttpSecChUaMobile(): string
    {
        return $this->getEnv('HTTP_SEC_CH_UA_MOBILE');
    }

    /**
     * Set HTTP_SEC_CH_UA_MOBILE value.
     *
     * @param string|null $value HTTP_SEC_CH_UA_MOBILE value to set, or null to use current request.
     * @return void
     */
    public function setHttpSecChUaMobile(?string $value = null): void
    {
        $this->setEnv('HTTP_SEC_CH_UA_MOBILE', $value);
    }

    /**
     * Get HTTP_SEC_CH_UA_PLATFORM value.
     *
     * @return string HTTP_SEC_CH_UA_PLATFORM value or default.
     */
    public function getHttpSecChUaPlatform(): string
    {
        return $this->getEnv('HTTP_SEC_CH_UA_PLATFORM');
    }

    /**
     * Set HTTP_SEC_CH_UA_PLATFORM value.
     *
     * @param string|null $value HTTP_SEC_CH_UA_PLATFORM value to set, or null to use current request.
     * @return void
     */
    public function setHttpSecChUaPlatform(?string $value = null): void
    {
        $this->setEnv('HTTP_SEC_CH_UA_PLATFORM', $value);
    }

    /**
     * Get HTTP_UPGRADE_INSECURE_REQUESTS value.
     *
     * @return string HTTP_UPGRADE_INSECURE_REQUESTS value or default.
     */
    public function getHttpUpgradeInsecureRequests(): string
    {
        return $this->getEnv('HTTP_UPGRADE_INSECURE_REQUESTS');
    }

    /**
     * Set HTTP_UPGRADE_INSECURE_REQUESTS value.
     *
     * @param string|null $value HTTP_UPGRADE_INSECURE_REQUESTS value to set, or null to use current request.
     * @return void
     */
    public function setHttpUpgradeInsecureRequests(?string $value = null): void
    {
        $this->setEnv('HTTP_UPGRADE_INSECURE_REQUESTS', $value);
    }

    /**
     * Get HTTP_USER_AGENT value.
     *
     * @return string HTTP_USER_AGENT value or default.
     */
    public function getUserAgent(): string
    {
        return $this->getEnv('HTTP_USER_AGENT');
    }

    /**
     * Set HTTP_USER_AGENT value.
     *
     * @param string|null $value HTTP_USER_AGENT value to set, or null to use current request.
     * @return void
     */
    public function setUserAgent(?string $value = null): void
    {
        $this->setEnv('HTTP_USER_AGENT', $value);
    }

    /**
     * Get HTTP_ACCEPT value.
     *
     * @return string HTTP_ACCEPT value or default.
     */
    public function getHttpAccept(): string
    {
        return $this->getEnv('HTTP_ACCEPT');
    }

    /**
     * Set HTTP_ACCEPT value.
     *
     * @param string|null $value HTTP_ACCEPT value to set, or null to use current request.
     * @return void
     */
    public function setHttpAccept(?string $value = null): void
    {
        $this->setEnv('HTTP_ACCEPT', $value);
    }

    /**
     * Get HTTP_SEC_FETCH_SITE value.
     *
     * @return string HTTP_SEC_FETCH_SITE value or default.
     */
    public function getHttpSecFetchSite(): string
    {
        return $this->getEnv('HTTP_SEC_FETCH_SITE');
    }

    /**
     * Set HTTP_SEC_FETCH_SITE value.
     *
     * @param string|null $value HTTP_SEC_FETCH_SITE value to set, or null to use current request.
     * @return void
     */
    public function setHttpSecFetchSite(?string $value = null): void
    {
        $this->setEnv('HTTP_SEC_FETCH_SITE', $value);
    }

    /**
     * Get HTTP_SEC_FETCH_MODE value.
     *
     * @return string HTTP_SEC_FETCH_MODE value or default.
     */
    public function getHttpSecFetchMode(): string
    {
        return $this->getEnv('HTTP_SEC_FETCH_MODE');
    }

    /**
     * Set HTTP_SEC_FETCH_MODE value.
     *
     * @param string|null $value HTTP_SEC_FETCH_MODE value to set, or null to use current request.
     * @return void
     */
    public function setHttpSecFetchMode(?string $value = null): void
    {
        $this->setEnv('HTTP_SEC_FETCH_MODE', $value);
    }

    /**
     * Get HTTP_SEC_FETCH_USER value.
     *
     * @return string HTTP_SEC_FETCH_USER value or default.
     */
    public function getHttpSecFetchUser(): string
    {
        return $this->getEnv('HTTP_SEC_FETCH_USER');
    }

    /**
     * Set HTTP_SEC_FETCH_USER value.
     *
     * @param string|null $value HTTP_SEC_FETCH_USER value to set, or null to use current request.
     * @return void
     */
    public function setHttpSecFetchUser(?string $value = null): void
    {
        $this->setEnv('HTTP_SEC_FETCH_USER', $value);
    }

    /**
     * Get HTTP_SEC_FETCH_DEST value.
     *
     * @return string HTTP_SEC_FETCH_DEST value or default.
     */
    public function getHttpSecFetchDest(): string
    {
        return $this->getEnv('HTTP_SEC_FETCH_DEST');
    }

    /**
     * Set HTTP_SEC_FETCH_DEST value.
     *
     * @param string|null $value HTTP_SEC_FETCH_DEST value to set, or null to use current request.
     * @return void
     */
    public function setHttpSecFetchDest(?string $value = null): void
    {
        $this->setEnv('HTTP_SEC_FETCH_DEST', $value);
    }

    /**
     * Get HTTP_REFERER value.
     *
     * @return string HTTP_REFERER value or default.
     */
    public function getHttpReferer(): string
    {
        return $this->getEnv('HTTP_REFERER');
    }

    /**
     * Set HTTP_REFERER value.
     *
     * @param string|null $value HTTP_REFERER value to set, or null to use current request.
     * @return void
     */
    public function setHttpReferer(?string $value = null): void
    {
        $this->setEnv('HTTP_REFERER', $value);
    }

    /**
     * Get HTTP_ACCEPT_ENCODING value.
     *
     * @return string HTTP_ACCEPT_ENCODING value or default.
     */
    public function getHttpAcceptEncoding(): string
    {
        return $this->getEnv('HTTP_ACCEPT_ENCODING');
    }

    /**
     * Set HTTP_ACCEPT_ENCODING value.
     *
     * @param string|null $value HTTP_ACCEPT_ENCODING value to set, or null to use current request.
     * @return void
     */
    public function setHttpAcceptEncoding(?string $value = null): void
    {
        $this->setEnv('HTTP_ACCEPT_ENCODING', $value);
    }

    /**
     * Get HTTP_ACCEPT_LANGUAGE value.
     *
     * @return string HTTP_ACCEPT_LANGUAGE value or default.
     */
    public function getHttpAcceptLanguage(): string
    {
        return $this->getEnv('HTTP_ACCEPT_LANGUAGE');
    }

    /**
     * Set HTTP_ACCEPT_LANGUAGE value.
     *
     * @param string|null $value HTTP_ACCEPT_LANGUAGE value to set, or null to use current request.
     * @return void
     */
    public function setHttpAcceptLanguage(?string $value = null): void
    {
        $this->setEnv('HTTP_ACCEPT_LANGUAGE', $value);
    }

    /**
     * Get HTTP_COOKIE value.
     *
     * @return string HTTP_COOKIE value or default.
     */
    public function getHttpCookie(): string
    {
        return $this->getEnv('HTTP_COOKIE');
    }

    /**
     * Set HTTP_COOKIE value.
     *
     * @param string|null $value HTTP_COOKIE value to set, or null to use current request.
     * @return void
     */
    public function setHttpCookie(?string $value = null): void
    {
        $this->setEnv('HTTP_COOKIE', $value);
    }

    /**
     * Get REQUEST_TIME_FLOAT value.
     *
     * @return float REQUEST_TIME_FLOAT value or default.
     */
    public function getRequestTimeFloat(): float
    {
        return $this->getEnv('REQUEST_TIME_FLOAT');
    }

    /**
     * Set REQUEST_TIME_FLOAT value.
     *
     * @param float|null $value REQUEST_TIME_FLOAT value to set, or null to use current request.
     * @return void
     */
    public function setRequestTimeFloat(?float $value = null): void
    {
        $this->setEnv('REQUEST_TIME_FLOAT', $value);
    }

    /**
     * Get REQUEST_TIME value.
     *
     * @return int REQUEST_TIME value or default.
     */
    public function getRequestTime(): int
    {
        return $this->getEnv('REQUEST_TIME');
    }

    /**
     * Set REQUEST_TIME value.
     *
     * @param int|null $value REQUEST_TIME value to set, or null to use current request.
     * @return void
     */
    public function setRequestTime(?int $value = null): void
    {
        $this->setEnv('REQUEST_TIME', $value);
    }

    /**
     * Get ORIGINAL_REQUEST_METHOD value.
     *
     * @return string ORIGINAL_REQUEST_METHOD value or default.
     */
    public function getOriginalRequestMethod(): string
    {
        return $this->getEnv('ORIGINAL_REQUEST_METHOD');
    }

    /**
     * Set ORIGINAL_REQUEST_METHOD value.
     *
     * @param string|null $value ORIGINAL_REQUEST_METHOD value to set, or null to use current request.
     * @return void
     */
    public function setOriginalRequestMethod(?string $value = null): void
    {
        $this->setEnv('ORIGINAL_REQUEST_METHOD', $value);
    }

    /**
     * Get HTTPS value.
     *
     * @return string HTTPS value or default.
     */
    public function getHttps(): string
    {
        return $this->getEnv('HTTPS');
    }

    /**
     * Set HTTPS value.
     *
     * @param string|null $value HTTPS value to set, or null to use current request.
     * @return void
     */
    public function setHttps(?string $value = null): void
    {
        $this->setEnv('HTTPS', $value);
    }

    /**
     * Get the browser name from the user agent string.
     *
     * @return string Browser name or 'Unknown'.
     */
    public function getBrowser(): string
    {
        $ua = $this->getEnv('HTTP_USER_AGENT');
        if (!$ua) {
            return 'Unknown';
        }
        // Simple browser detection
        if (stripos($ua, 'Edg') !== false) {
            return 'Edg';
        }
        if (stripos($ua, 'OPR') !== false || stripos($ua, 'Opera') !== false) {
            return 'Opera';
        }
        if (stripos($ua, 'Chrome') !== false) {
            return 'Chrome';
        }
        if (stripos($ua, 'Safari') !== false) {
            return 'Safari';
        }
        if (stripos($ua, 'Firefox') !== false) {
            return 'Firefox';
        }
        if (stripos($ua, 'MSIE') !== false || stripos($ua, 'Trident') !== false) {
            return 'Internet Explorer';
        }

        return 'Unknown';
    }

    /**
     * Get the operating system from the user agent string.
     *
     * @return string OS name or 'Unknown'.
     */
    public function getOs(): string
    {
        $ua = $this->getEnv('HTTP_USER_AGENT');
        if (!$ua) {
            return 'Unknown';
        }
        if (preg_match('/windows nt/i', $ua)) {
            return 'Windows';
        }
        if (preg_match('/macintosh|mac os x/i', $ua)) {
            return 'Mac OS';
        }
        if (preg_match('/linux/i', $ua)) {
            return 'Linux';
        }
        if (preg_match('/iphone|ipad|ipod/i', $ua)) {
            return 'iOS';
        }
        if (preg_match('/android/i', $ua)) {
            return 'Android';
        }

        return 'Unknown';
    }

    /**
     * Get the device type from the user agent string.
     *
     * @return string Device type: 'Mobile', 'Tablet', 'Desktop', or 'Unknown'.
     */
    public function getDevice(): string
    {
        $ua = $this->getEnv('HTTP_USER_AGENT');
        if (!$ua) {
            return 'Unknown';
        }
        if (preg_match('/mobile/i', $ua)) {
            return 'Mobile';
        }
        if (preg_match('/tablet|ipad/i', $ua)) {
            return 'Tablet';
        }
        if (preg_match('/windows|macintosh|linux/i', $ua)) {
            return 'Desktop';
        }

        return 'Unknown';
    }
}
