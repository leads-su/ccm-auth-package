<?php

namespace ConsulConfigManager\Auth\Services;

use WhichBrowser\Parser;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

/**
 * Class TokenNameGenerator
 * @package ConsulConfigManager\Auth\Services
 */
class TokenNameGenerator
{
    /**
     * Generator instance reference
     * @var TokenNameGenerator|null
     */
    private static ?TokenNameGenerator $instance = null;

    /**
     * User agent reference string
     * @var string
     */
    private string $userAgent;

    /**
     * Request instance
     * @var Request
     */
    private Request $request;

    /**
     * Parser instance
     * @var Parser
     */
    private Parser $parser;

    /**
     * List of token name parts
     * @var array
     */
    private array $parts = [];

    /**
     * Create new generator instance
     * @param string $userAgent
     * @return static
     */
    public static function from(string $userAgent): self
    {
        if (self::$instance === null) {
            self::$instance = new self($userAgent);
        }
        return self::$instance;
    }

    /**
     * Convert parser class instance to array
     * @return array
     */
    public function toArrayFromParser(): array
    {
        return $this->parser->toArray();
    }

    /**
     * Get token parts array
     * @return array
     */
    public function toArray(): array
    {
        return $this->parts;
    }

    /**
     * Convert token parts array to string
     * @return string
     */
    public function toString(): string
    {
        return implode(' ', $this->parts);
    }

    /**
     * Convert token parts array to json string
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->parts);
    }

    /**
     * TokenNameGenerator constructor.
     * @param string $userAgent
     * @param Request|null $request
     */
    protected function __construct(string $userAgent, ?Request $request = null)
    {
        $this->userAgent = $userAgent;
        $this->parser = new Parser($this->userAgent);
        if ($request === null) {
            $request = request();
        }
        $this->request = $request;
        $this->process();
    }

    /**
     * Process user agent and populate token name parts array
     * @return void
     */
    private function process(): void
    {
        $manufacturer = $this->parser->device->getManufacturer();
        if ($manufacturer !== null && strlen($manufacturer) > 0) {
            $this->append('manufacturer', $manufacturer);
        }

        $model =  $this->parser->isMobile() ? $this->parser->device->getModel() : null;
        if ($model !== null && strlen($model) > 0) {
            $this->append('model', $model);
        }

        $osName = $this->parser->os->getName();
        if ($osName !== null && strlen($osName) > 0) {
            $this->append('os_name', $osName);
        }

        $osVersion = $this->parser->os->getVersion();
        if ($osVersion !== null && strlen($osVersion) > 0) {
            $this->append('os_version', $osVersion);
        }

        $browserName = $this->parser->browser->getName();
        if ($browserName !== null && strlen($browserName) > 0) {
            $this->append('browser_name', $browserName);
        }

        $browserVersion = $this->parser->browser->getVersion();
        if ($browserVersion !== null && strlen($browserVersion) > 0) {
            $this->append('browser_version', $browserVersion);
        }

        if ($this->request->ip() !== null) {
            $this->append('ip_address', $this->request->ip());
        } else {
            $ips = $this->request->ips();
            $ip = 'unknown';
            if (count($ips) > 0) {
                $ip = Arr::first($ips);
            }
            $this->append('ip_address', $ip);
        }
    }

    /**
     * Append string to token name parts list
     * @param string $key
     * @param string $string
     * @return void
     */
    private function append(string $key, string $string): void
    {
        $this->parts[$key] = $string;
    }
}
