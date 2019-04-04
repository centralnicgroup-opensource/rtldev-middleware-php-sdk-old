<?php
declare(strict_types=1);

/**
 * HEXONET
 * Copyright © HEXONET
 */

namespace HEXONET;

use \HEXONET\ResponseTemplateManager as RTM;

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

    public function __construct()
    {
        $this->socketURL = "";
        $this->debugMode = false;
        $this->ua = "";
        $this->setURL("https://coreapi.1api.net/api/call.cgi");
        $this->socketConfig = new SocketConfig();
        $this->useLIVESystem();
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
     * Get the current module version
     * @return string module version
     */
    public function getVersion()
    {
        return "4.4.0";
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
     * Perform API request using the given command
     * @param array $cmd API command to request
     * @return Response Response
     */
    public function request($cmd)
    {
        $curl = curl_init($this->socketURL);
        $data = $this->getPOSTData($cmd);
        if ($curl === false) {
            $r = RTM::getInstance()->getTemplate("nocurl").getPlain();
            if ($this->debugMode) {
                echo $this->socketURL . "\n";
                echo $data . "\n";
                echo "CURL Not available\n";
                echo $r . "\n";
            }
            return new Response($r, $cmd);
        }
        curl_setopt_array($curl, array(
            //timeout: APIClient.socketTimeout,
            CURLOPT_POST            =>  1,
            //CURLOPT_POSTFIELDS      =>  gzencode($this->getPOSTData($cmd)),
            CURLOPT_POSTFIELDS      =>  $this->getPOSTData($cmd),
            CURLOPT_HEADER          =>  0,
            CURLOPT_RETURNTRANSFER  =>  1,
            //CURLOPT_ENCODING        => 'gzip',
            CURLOPT_USERAGENT       =>  $this->getUserAgent(),
            CURLOPT_HTTPHEADER      =>  array(
                'Expect:',
                'Content-type: text/html; charset=UTF-8',
                //'Content-Encoding: gzip',
                //'Accept-Encoding: gzip'
            )
        ));
        $r = curl_exec($curl);
        $r = ($r===false) ?
            RTM::getInstance()->getTemplate("httperror").getPlain() :
            //gzdecode($r);
            $r;

        //"If both Content-Length and Transfer-Encoding headers are missing,
        //then at the end of the response the connection must be closed."
        //-> That's what we do
        curl_close($curl);
        if ($this->debugMode) {
            echo $this->socketURL . "\n";
            echo $data . "\n";
            echo $r . "\n";
        }
        return new Response($r, $cmd);
    }

    /**
     * Request the next page of list entries for the current list query
     * Useful for tables
     * @param Response $rr API Response of current page
     * @return Response|null Response or null in case there are no further list entries
     */
    public function requestNextResponsePage($rr)
    {
        $mycmd = $this->toUpperCaseKeys($rr->getCommand());
        if (array_key_exists("LAST", $mycmd)) {
            throw new \Error("Parameter LAST in use. Please remove it to avoid issues in requestNextPage.");
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
