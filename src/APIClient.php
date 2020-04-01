<?php
#declare(strict_types=1);

/**
 * HEXONET
 * Copyright © HEXONET
 */

namespace HEXONET;

use \HEXONET\ResponseTemplateManager as RTM;
use \HEXONET\Logger as L;

// check the docs, don't worry about http usage here
define("ISPAPI_CONNECTION_URL_PROXY", "http://127.0.0.1/api/call.cgi");
define("ISPAPI_CONNECTION_URL", "https://api.ispapi.net/api/call.cgi");

/**
 * HEXONET APIClient
 *
 * @package HEXONET
 */

class APIClient
{
    /**
     * API connection timeout setting
     * @var integer
     */
    private static $socketTimeout = 300000;
    /**
     * API connection url
     * @var string
     */
    private $socketURL;
    /**
     * Object covering API connection data
     * @var SocketConfig
     */
    private $socketConfig;
    /**
     * activity flag for debug mode
     * @var boolean
     */
    private $debugMode;
    /**
     * user agent
     * @var string
     */
    private $ua;
    /**
     * additional curl options to use
     */
    private $curlopts = [];

    /**
     * logger function name for debug mode
     */
    private $logger;

    public function __construct()
    {
        $this->socketURL = "";
        $this->debugMode = false;
        $this->ua = "";
        $this->setURL(ISPAPI_CONNECTION_URL);
        $this->socketConfig = new SocketConfig();
        $this->useLIVESystem();
        $this->setDefaultLogger();
    }

    /**
     * set custom logger to use instead of default one
     * create your own class inheriting from \HEXONET\Logger and overriding method log
     * @param Logger $customLogger
     * @return $this
     */
    public function setCustomLogger($customLogger)
    {
        $this->logger = $customLogger;
        return $this;
    }

    /**
     * set default logger to use
     * @return $this
     */
    public function setDefaultLogger()
    {
        $this->logger = new L();
        return $this;
    }

    /**
     * Enable Debug Output to STDOUT
     * @return $this
     */
    public function enableDebugMode()
    {
        $this->debugMode = true;
        return $this;
    }

    /**
     * Disable Debug Output
     * @return $this
     */
    public function disableDebugMode()
    {
        $this->debugMode = false;
        return $this;
    }

    /**
     * Serialize given command for POST request including connection configuration data
     * @param string|array $cmd API command to encode
     * @return string encoded POST data string
     */
    public function getPOSTData($cmd)
    {
        $data = $this->socketConfig->getPOSTData();
        $tmp = "";
        if (!is_string($cmd)) {
            foreach ($cmd as $key => $val) {
                if (isset($val)) {
                    $tmp .= $key . "=" . preg_replace("/\r|\n/", "", $val) . "\n";
                }
            }
        }
        $tmp = preg_replace("/\n$/", "", $tmp);
        $data .= rawurlencode("s_command") . "=" . rawurlencode($tmp);
        return $data;
    }

    /**
     * Get the API Session ID that is currently set
     * @return string|null API Session ID currently in use
     */
    public function getSession()
    {
        $sessid = $this->socketConfig->getSession();
        return ($sessid === "" ? null : $sessid);
    }

    /**
     * Get the API connection url that is currently set
     * @return string API connection url currently in use
     */
    public function getURL()
    {
        return $this->socketURL;
    }

    /**
     * Set a custom user agent (for platforms that use this SDK)
     * @param string user agent label
     * @param string user agent revision
     * @return $this
     */
    public function setUserAgent($str, $rv)
    {
        $this->ua = $str . " (" . PHP_OS . "; " . php_uname('m') . "; rv:" . $rv . ") php-sdk/" . $this->getVersion() . " php/" . PHP_VERSION;
        return $this;
    }

    /**
     * Get the user agent string
     * @return string user agent string
     */
    public function getUserAgent()
    {
        if (!strlen($this->ua)) {
            $this->ua = "PHP-SDK (". PHP_OS . "; ". php_uname('m') . "; rv:" . $this->getVersion() . ") php/" . PHP_VERSION;
        }
        return $this->ua;
    }

    /**
     * Set proxy to use for API communication
     * @param string $proxy proxy to use
     * @return $this
     */
    public function setProxy($proxy)
    {
        $this->curlopts[CURLOPT_PROXY] = $proxy;
        return $this;
    }
    
    /**
     * Get proxy configuration for API communication
     * @return string|null
     */
    public function getProxy()
    {
        if (isset($this->curlopts[CURLOPT_PROXY])) {
            return $this->curlopts[CURLOPT_PROXY];
        }
        return null;
    }

    /**
     * Set Referer to use for API communication
     * @param string $referer Referer
     * @return $this
     */
    public function setReferer($referer)
    {
        $this->curlopts[CURLOPT_REFERER] = $referer;
        return $this;
    }

    /**
     * Get Referer configuration for API communication
     * @return string|null
     */
    public function getReferer()
    {
        if (isset($this->curlopts[CURLOPT_REFERER])) {
            return $this->curlopts[CURLOPT_REFERER];
        }
        return null;
    }

    /**
     * Get the current module version
     * @return string module version
     */
    public function getVersion()
    {
        return "5.4.2";
    }

    /**
     * Apply session data (session id and system entity) to given php session object
     * @param array $session php session instance ($_SESSION)
     * @return $this
     */
    public function saveSession(&$session)
    {
        $session["socketcfg"] = array(
            "entity" => $this->socketConfig->getSystemEntity(),
            "session" => $this->socketConfig->getSession()
        );
        return $this;
    }

    /**
     * Use existing configuration out of php session object
     * to rebuild and reuse connection settings
     * @param array $session php session object ($_SESSION)
     * @return $this
     */
    public function reuseSession(&$session)
    {
        $this->socketConfig->setSystemEntity($session["socketcfg"]["entity"]);
        $this->setSession($session["socketcfg"]["session"]);
        return $this;
    }

    /**
     * Set another connection url to be used for API communication
     * @param string $value API connection url to set
     * @return $this
     */
    public function setURL($value)
    {
        $this->socketURL = $value;
        return $this;
    }

    /**
     * Set one time password to be used for API communication
     * @param string $value one time password
     * @return $this
     */
    public function setOTP($value)
    {
        $this->socketConfig->setOTP($value);
        return $this;
    }

    /**
     * Set an API session id to be used for API communication
     * @param string $value API session id
     * @return $this
     */
    public function setSession($value)
    {
        $this->socketConfig->setSession($value);
        return $this;
    }

    /**
     * Set an Remote IP Address to be used for API communication
     * To be used in case you have an active ip filter setting.
     * @param string $value Remote IP Address
     * @return $this
     */
    public function setRemoteIPAddress($value)
    {
        $this->socketConfig->setRemoteAddress($value);
        return $this;
    }

    /**
     * Set Credentials to be used for API communication
     * @param string $uid account name
     * @param string $pw account password
     * @return $this
     */
    public function setCredentials($uid, $pw)
    {
        $this->socketConfig->setLogin($uid);
        $this->socketConfig->setPassword($pw);
        return $this;
    }

    /**
     * Set Credentials to be used for API communication
     * @param string $uid account name
     * @param string $role role user id
     * @param string $pw role user password
     * @return $this
     */
    public function setRoleCredentials($uid, $role, $pw)
    {
        return $this->setCredentials(!empty($role) ? $uid . "!" . $role : $uid, $pw);
    }

    /**
     * Perform API login to start session-based communication
     * @param string $otp optional one time password
     * @return Response Response
     */
    public function login($otp = "")
    {
        $this->setOTP($otp);
        $rr = $this->request(array("COMMAND" => "StartSession"));
        if ($rr->isSuccess()) {
            $col = $rr->getColumn("SESSION");
            $this->setSession($col ? $col->getData()[0] : "");
        }
        return $rr;
    }

    /**
     * Perform API login to start session-based communication.
     * Use given specific command parameters.
     * @param array $params given specific command parameters
     * @param string $otp optional one time password
     * @return Response Response
     */
    public function loginExtended($params, $otp = "")
    {
        $this->setOTP($otp);
        $rr = $this->request(array_merge(
            array("COMMAND" => "StartSession"),
            $params
        ));
        if ($rr->isSuccess()) {
            $col = $rr->getColumn("SESSION");
            $this->setSession($col ? $col->getData()[0] : "");
        }
        return $rr;
    }

    /**
     * Perform API logout to close API session in use
     * @return Response Response
     */
    public function logout()
    {
        $rr = $this->request(array("COMMAND" => "EndSession"));
        if ($rr->isSuccess()) {
            $this->setSession("");
        }
        return $rr;
    }

    /**
     * Flatten API command's nested arrays for easier handling
     * @param array $cmd API Command
     * @return array
     */
    private function flattenCommand($cmd)
    {
        $mycmd = $this->toUpperCaseKeys($cmd);
        $newcmd = [];
        foreach ($mycmd as $key => $val) {
            if (isset($val)) {
                $val = preg_replace("/\r|\n/", "", $val);
                if (is_array($val)) {
                    foreach ($cmd[$key] as $idx => $v) {
                        $newcmd[$key.$idx] = $v;
                    }
                } else {
                    $newcmd[$key] = $val;
                }
            }
        }
        return $newcmd;
    }

    /**
     * Auto convert API command parameters to punycode, if necessary.
     * @param array $cmd API command
     * @return array
     */
    private function autoIDNConvert($cmd)
    {
        // don't convert for convertidn command to avoid endless loop
        // and ignore commands in string format (even deprecated)
        if (is_string($cmd) || preg_match("/^CONVERTIDN$/i", $cmd["COMMAND"])) {
            return $cmd;
        }
        $toconvert = [];
        $keys = preg_grep("/^(DOMAIN|NAMESERVER|DNSZONE)([0-9]*)$/i", array_keys($cmd));
        if (empty($keys)) {
            return $cmd;
        }
        $idxs = [];
        foreach ($keys as $key) {
            if (isset($cmd[$key])) {
                $cmd[$key] = preg_replace("/\r|\n/", "", $cmd[$key]);
                if (preg_match('/[^a-z0-9\.\- ]/i', $cmd[$key])) {// maybe preg_grep as replacement
                    $toconvert[] = $cmd[$key];
                    $idxs[] = $key;
                }
            }
        }
        $r = $this->request([
            "COMMAND" => "ConvertIDN",
            "DOMAIN" => $toconvert
        ]);
        if ($r->isSuccess()) {
            $col = $r->getColumn("ACE");
            if ($col) {
                foreach ($col->getData() as $idx => $pc) {
                    $cmd[$idxs[$idx]] = $pc;
                }
            }
        }
        return $cmd;
    }

    /**
     * Perform API request using the given command
     * @param array $cmd API command to request
     * @return Response Response
     */
    public function request($cmd)
    {
        // flatten nested api command bulk parameters
        $mycmd = $this->flattenCommand($cmd);
        // auto convert umlaut names to punycode
        $mycmd = $this->autoIDNConvert($mycmd);
        
        // request command to API
        $cfg = [
            "CONNECTION_URL" => $this->socketURL
        ];
        $curl = curl_init($this->socketURL);
        $data = $this->getPOSTData($mycmd);
        if ($curl === false) {
            $r = RTM::getInstance()->getTemplate("nocurl")->getPlain();
            if ($this->debugMode) {
                $this->logger->log($data, $r, "CURL for PHP missing.");
            }
            return new Response($r, $mycmd, $cfg);
        }
        curl_setopt_array($curl, array(
            //timeout: APIClient.socketTimeout,
            //CURLOPT_POSTFIELDS      =>  gzencode($this->getPOSTData($cmd)),
            //CURLOPT_ENCODING        => 'gzip',
            CURLOPT_POST            =>  1,
            CURLOPT_POSTFIELDS      =>  $data,
            CURLOPT_HEADER          =>  0,
            CURLOPT_RETURNTRANSFER  =>  1,
            CURLOPT_USERAGENT       =>  $this->getUserAgent(),
            CURLOPT_HTTPHEADER      =>  array(
                'Expect:',
                'Content-type: text/html; charset=UTF-8',
                //'Content-Encoding: gzip',
                //'Accept-Encoding: gzip'
            )
        ) + $this->curlopts);
        $r = curl_exec($curl);
        $r = ($r===false) ?
            RTM::getInstance()->getTemplate("httperror")->getPlain() :
            //gzdecode($r);
            $r;

        //"If both Content-Length and Transfer-Encoding headers are missing,
        //then at the end of the response the connection must be closed."
        //-> That's what we do
        curl_close($curl);
        if ($this->debugMode) {
            $this->logger->log($data, new Response($r, $mycmd, $cfg));
        }
        return new Response($r, $mycmd, $cfg);
    }

    /**
     * Request the next page of list entries for the current list query
     * Useful for tables
     * @param Response $rr API Response of current page
     * @throws Exception in case Command Parameter LAST is in use while using this method
     * @return Response|null Response or null in case there are no further list entries
     */
    public function requestNextResponsePage($rr)
    {
        $mycmd = $rr->getCommand();
        if (array_key_exists("LAST", $mycmd)) {
            throw new \Exception("Parameter LAST in use. Please remove it to avoid issues in requestNextPage.");
        }
        $first = 0;
        if (array_key_exists("FIRST", $mycmd)) {
            $first = $mycmd["FIRST"];
        }
        $total = $rr->getRecordsTotalCount();
        $limit = $rr->getRecordsLimitation();
        $first += $limit;
        if ($first < $total) {
            $mycmd["FIRST"] = $first;
            $mycmd["LIMIT"] = $limit;
            return $this->request($mycmd);
        } else {
            return null;
        }
    }

    /**
     * Request all pages/entries for the given query command
     * @param array $cmd API list command to use
     * @return Response[] Responses
     */
    public function requestAllResponsePages($cmd)
    {
        $responses = array();
        $rr = $this->request(array_merge(array(), $cmd, array("FIRST" => 0)));
        $tmp = $rr;
        $idx = 0;
        do {
            $responses[$idx++] = $tmp;
            $tmp = $this->requestNextResponsePage($tmp);
        } while ($tmp !== null);
        return $responses;
    }

    /**
     * Set a data view to a given subuser
     * @param string $uid subuser account name
     * @return $this
     */
    public function setUserView($uid = '')
    {
        $this->socketConfig->setUser($uid);
        return $this;
    }

    /**
     * Reset data view back from subuser to user
     * @return $this
     */
    public function resetUserView()
    {
        $this->socketConfig->setUser("");
        return $this;
    }

    /**
     * Activate High Performance Setup
     * @return $this
     */
    public function useHighPerformanceConnectionSetup()
    {
        $this->setURL(ISPAPI_CONNECTION_URL_PROXY);
        return $this;
    }


    /**
     * Activate Default Connection Setup (which is the default anyways)
     * @return $this
     */
    public function useDefaultConnectionSetup()
    {
        $this->setURL(ISPAPI_CONNECTION_URL);
        return $this;
    }

    /**
     * Set OT&E System for API communication
     * @return $this
     */
    public function useOTESystem()
    {
        $this->socketConfig->setSystemEntity("1234");
        return $this;
    }

    /**
     * Set LIVE System for API communication (this is the default setting)
     * @return $this
     */
    public function useLIVESystem()
    {
        $this->socketConfig->setSystemEntity("54cd");
        return $this;
    }

    /**
     * Translate all command parameter names to uppercase
     * @param array $cmd api command
     * @return array api command with uppercase parameter names
     */
    private function toUpperCaseKeys($cmd)
    {
        return array_change_key_case($cmd, CASE_UPPER);
    }
}
