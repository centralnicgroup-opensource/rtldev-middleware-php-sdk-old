<?php
declare(strict_types=1);

/**
 * HEXONET
 * Copyright © HEXONET
 */

namespace HEXONET;

use \HEXONET\ResponseParser as RP;

/**
 * HEXONET ResponseTemplateManager
 *
 * @package HEXONET
 */

final class ResponseTemplateManager
{
    /**
     * Get ResponseTemplateManager Instance
     * @return self
     */
    public static function getInstance()
    {
        if (null === static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }
    /**
     * ResponseTemplateManager Instance
     * @var ResponseTemplateManager|null
     */
    private static $_instance = null;
    /**
     * template container
     * @var array[string]string
     */
    private $templates;

    /**
    * clone
    * Forbid creating a copy of that instance from outside
    */
    private function __clone()
    {
    }


    /**
    * wakeup
    * prevent from being unserialized (which would create a second instance of it)
    */
    private function __wakeup()
    {
    }

    /**
     * Constructor
     * Forbid creating an instance from outside
     */
    private function __construct()
    {
        $this->templates = array(
            "404" => $this->generateTemplate("421", "Page not found"),
            "500" => $this->generateTemplate("500", "Internal server error"),
            "empty" => $this->generateTemplate("423", "Empty API response"),
            "error" => $this->generateTemplate("421", "Command failed due to server error. Client should try again"),
            "expired" => $this->generateTemplate("530", "SESSION NOT FOUND"),
            "httperror" => $this->generateTemplate("421", "Command failed due to HTTP communication error"),
            "nocurl" => $this->generateTemplate("423", "API access error: curl_init failed"),
            "unauthorized" => $this->generateTemplate("530", "Unauthorized")
        );
    }

    /**
     * Generate API response template string for given code and description
     * @param string $code API response code
     * @param string $description API response description
     * @return string generate response template string
     */
    public function generateTemplate($code, $description)
    {
        return "[RESPONSE]\r\nCODE=" . $code . "\r\nDESCRIPTION=" . $description . "\r\nEOF\r\n";
    }

    /**
     * Add response template to template container
     * @param string $id template id
     * @param string $plain API plain response
     * @return self
     */
    public function addTemplate($id, $plain)
    {
        $this->templates[$id] = $plain;
        return self::$_instance;
    }

    /**
     * Get response template instance from template container
     * @param string $id template id
     * @return ResponseTemplate template instance
     */
    public function getTemplate($id)
    {
        if ($this->hasTemplate($id)) {
            return new ResponseTemplate($this->templates[$id]);
        }
        return new ResponseTemplate(
            $this->generateTemplate("500", "Response Template not found")
        );
    }

    /**
     * Return all available response templates
     * @return array[string]ResponseTemplate all available response template instances
     */
    public function getTemplates()
    {
        $tpls = array();
        foreach ($this->templates as $key => $raw) {
            $tpls[$key] = new ResponseTemplate($raw);
        }
        return $tpls;
    }

    /**
     * Check if given template exists in template container
     * @param string $id template id
     * @return boolean boolean result
     */
    public function hasTemplate($id)
    {
        return array_key_exists($id, $this->templates);
    }

    /**
     * Check if given API response hash matches a given template by code and description
     * @param array[string]string $tpl api response hash
     * @param string $id template id
     * @return boolean boolean result
     */
    public function isTemplateMatchHash($tpl, $id)
    {
        $h = $this->getTemplate($id)->getHash();
        return (
            ($h["CODE"] === $tpl["CODE"]) &&
            ($h["DESCRIPTION"] === $tpl["DESCRIPTION"])
        );
    }

    /**
     * Check if given API plain response matches a given template by code and description
     * @param string $plain API plain response
     * @param string $id template id
     * @return boolean boolean result
     */
    public function isTemplateMatchPlain($plain, $id)
    {
        $h = $this->getTemplate($id)->getHash();
        $tpl = RP\parse($plain);
        return (
            ($h["CODE"] === $tpl["CODE"]) &&
            ($h["DESCRIPTION"] === $tpl["DESCRIPTION"])
        );
    }
}
