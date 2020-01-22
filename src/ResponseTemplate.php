<?php
#declare(strict_types=1);

/**
 * HEXONET
 * Copyright © HEXONET
 */

namespace HEXONET;

use \HEXONET\ResponseParser as RP;
use \HEXONET\ResponseTemplateManager as RTM;

/**
 * HEXONET ResponseTemplate
 *
 * @package HEXONET
 */

class ResponseTemplate
{
    /**
     * plain API response
     * @var string
     */
    protected $raw;
    /**
     * hash representation of plain API response
     * @var array
     */
    protected $hash;

    /**
     * Constructor
     * @param string $raw plain API response
     */
    public function __construct($raw)
    {
        if (!$raw) {
            $raw = RTM::getInstance()->getTemplate("empty")->getPlain();
        }
        $this->raw = $raw;
        $this->hash = RP::parse($raw);
    }

    /**
     * Get API response code
     * @return integer API response code
     */
    public function getCode()
    {
        return intval($this->hash["CODE"], 10);
    }

    /**
     * Get API response description
     * @return string API response description
     */
    public function getDescription()
    {
        return $this->hash["DESCRIPTION"];
    }

    /**
     * Get Plain API response
     * @return string Plain API response
     */
    public function getPlain()
    {
        return $this->raw;
    }

    /**
     * Get Queuetime of API response
     * @return float Queuetime of API response
     */
    public function getQueuetime()
    {
        if (array_key_exists("QUEUETIME", $this->hash)) {
            return floatval($this->hash["QUEUETIME"]);
        }
        return 0.00;
    }

    /**
     * Get API response as Hash
     * @return array API response hash
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Get Runtime of API response
     * @return float Runtime of API response
     */
    public function getRuntime()
    {
        if (array_key_exists("RUNTIME", $this->hash)) {
            return floatval($this->hash["RUNTIME"]);
        }
        return 0.00;
    }

    /**
     * Check if current API response represents an error case
     * API response code is an 5xx code
     * @return boolean boolean result
     */
    public function isError()
    {
        return substr($this->hash["CODE"], 0, 1) === "5";
    }

    /**
     * Check if current API response represents a success case
     * API response code is an 2xx code
     * @return boolean boolean result
     */
    public function isSuccess()
    {
        return substr($this->hash["CODE"], 0, 1) === "2";
    }

    /**
     * Check if current API response represents a temporary error case
     * API response code is an 4xx code
     * @return boolean result
     */
    public function isTmpError()
    {
        return substr($this->hash["CODE"], 0, 1) === "4";
    }

    /**
     * Check if current operation is returned as pending
     * @return boolean result
     */
    public function isPending()
    {
        return isset($this->hash["PENDING"]) ? $this->hash["PENDING"] === "1" : false;
    }
}
