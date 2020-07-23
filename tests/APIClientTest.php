<?php
//declare(strict_types=1);

namespace HEXONETTEST;

use \HEXONET\APIClient as CL;
use \HEXONET\Response as R;

final class APIClientTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \HEXONET\APIClient|null $cl
     */
    public static $cl;

    public static function setUpBeforeClass(): void
    {
        session_start();
        self::$cl = new CL();
    }

    public static function tearDownAfterClass(): void
    {
        self::$cl = null;
        session_destroy();
    }

    public function testGetPOSTDataSecured(): void
    {
        self::$cl->setCredentials('test.user', 'test.passw0rd');
        $enc = self::$cl->getPOSTData(array(
            'COMMAND' => 'CheckAuthentication',
            'SUBUSER' => 'test.user',
            'PASSWORD' => 'test.passw0rd'
        ), true);
        self::$cl->setCredentials('', '');
        $this->assertEquals('s_entity=54cd&s_login=test.user&s_pw=***&s_command=COMMAND%3DCheckAuthentication%0ASUBUSER%3Dtest.user%0APASSWORD%3D%2A%2A%2A', $enc);
    }

    public function testGetPOSTDataObj(): void
    {
        $enc = self::$cl->getPOSTData(array(
            'COMMAND' => 'ModifyDomain',
            'AUTH' => 'gwrgwqg%&\\44t3*'
        ));
        $this->assertEquals($enc, 's_entity=54cd&s_command=COMMAND%3DModifyDomain%0AAUTH%3Dgwrgwqg%25%26%5C44t3%2A');
    }

    public function testGetPOSTDataStr(): void
    {
        $enc = self::$cl->getPOSTData('gregergege');
        $this->assertEquals($enc, 's_entity=54cd&s_command=gregergege');
    }

    public function testGetPOSTDataNull(): void
    {
        $enc = self::$cl->getPOSTData(array(
            'COMMAND' => 'ModifyDomain',
            'AUTH' => null
        ));
        $this->assertEquals($enc, 's_entity=54cd&s_command=COMMAND%3DModifyDomain');
    }

    public function testEnableDebugMode(): void
    {
        self::$cl->enableDebugMode();
        $this->assertEquals(1, 1);//suppress warning for risky test
    }

    public function testDisableDebugMode(): void
    {
        self::$cl->disableDebugMode();
        $this->assertEquals(1, 1);//suppress warning for risky test
    }

    public function testGetSession(): void
    {
        $sessid = self::$cl->getSession();
        $this->assertNull($sessid);
    }

    public function testGetSessionIDSet(): void
    {
        $sess = 'testsession12345';
        $sessid = self::$cl->setSession($sess)->getSession();
        $this->assertEquals($sessid, $sess);
        self::$cl->setSession('');
    }

    public function testGetURL(): void
    {
        $url = self::$cl->getURL();
        $this->assertEquals($url, ISPAPI_CONNECTION_URL);
    }

    public function testGetUserAgent(): void
    {
        $ua = "PHP-SDK (". PHP_OS . "; ". php_uname('m') . "; rv:" . self::$cl->getVersion() . ") php/" . implode(".", [PHP_MAJOR_VERSION, PHP_MINOR_VERSION, PHP_RELEASE_VERSION]);
        $this->assertEquals(self::$cl->getUserAgent(), $ua);
    }

    public function testSetUserAgent(): void
    {
        $pid = "WHMCS";
        $rv = "7.7.0";
        $ua = $pid . " (". PHP_OS . "; ". php_uname('m') . "; rv:" . $rv . ") php-sdk/" . self::$cl->getVersion() . " php/" . implode(".", [PHP_MAJOR_VERSION, PHP_MINOR_VERSION, PHP_RELEASE_VERSION]);
        $cls = self::$cl->setUserAgent($pid, $rv);
        $this->assertInstanceOf(CL::class, $cls);
        $this->assertEquals(self::$cl->getUserAgent(), $ua);
    }

    public function testSetUserAgentModules(): void
    {
        $pid = "WHMCS";
        $rv = "7.7.0";
        $mods = ["reg/2.6.2", "ssl/7.2.2", "dc/8.2.2"];
        $ua = $pid . " (". PHP_OS . "; ". php_uname('m') . "; rv:" . $rv . ") " . implode(" ", $mods) . " php-sdk/" . self::$cl->getVersion() . " php/" . implode(".", [PHP_MAJOR_VERSION, PHP_MINOR_VERSION, PHP_RELEASE_VERSION]);
        $cls = self::$cl->setUserAgent($pid, $rv, $mods);
        $this->assertInstanceOf(CL::class, $cls);
        $this->assertEquals(self::$cl->getUserAgent(), $ua);
    }

    public function testSetURL(): void
    {
        $url = self::$cl->setURL(ISPAPI_CONNECTION_URL_PROXY)->getURL();
        $this->assertEquals($url, ISPAPI_CONNECTION_URL_PROXY);
        self::$cl->setURL(ISPAPI_CONNECTION_URL);
    }

    public function testSetOTPSet(): void
    {
        self::$cl->setOTP('12345678');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_otp=12345678&s_command=COMMAND%3DStatusAccount');
    }

    public function testSetOTPReset(): void
    {
        self::$cl->setOTP('');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_command=COMMAND%3DStatusAccount');
    }

    public function testSetSessionSet(): void
    {
        self::$cl->setSession('12345678');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_session=12345678&s_command=COMMAND%3DStatusAccount');
    }

    public function testSetSessionCredentials(): void
    {
        // credentials and otp code have to be unset when session id is set
        self::$cl->setRoleCredentials('myaccountid', 'myrole', 'mypassword')
                ->setOTP('12345678')
                ->setSession('12345678');
        $tmp = self::$cl->getPOSTData(array(
            'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_session=12345678&s_command=COMMAND%3DStatusAccount');
    }

    public function testSetSessionReset(): void
    {
        self::$cl->setSession('');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_command=COMMAND%3DStatusAccount');
    }

    public function testSaveReuseSession(): void
    {
        self::$cl->setSession('12345678')
                ->saveSession($_SESSION);
        $cl2 = new CL();
        $cl2->reuseSession($_SESSION);
        $tmp = $cl2->getPOSTData(array(
            'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_session=12345678&s_command=COMMAND%3DStatusAccount');
        self::$cl->setSession('');
    }

    public function testSetRemoteIPAddressSet(): void
    {
        self::$cl->setRemoteIPAddress('10.10.10.10');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_remoteaddr=10.10.10.10&s_command=COMMAND%3DStatusAccount');
    }

    public function testSetRemoteIPAddressReset(): void
    {
        self::$cl->setRemoteIPAddress('');
        $tmp = self::$cl->getPOSTData(array(
            'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_command=COMMAND%3DStatusAccount');
    }

    public function testSetCredentialsSet(): void
    {
        self::$cl->setCredentials('myaccountid', 'mypassword');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_login=myaccountid&s_pw=mypassword&s_command=COMMAND%3DStatusAccount');
    }

    public function testSetCredentialsReset(): void
    {
        self::$cl->setCredentials('', '');
        $tmp = self::$cl->getPOSTData(array(
            'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_command=COMMAND%3DStatusAccount');
    }

    public function testSetRoleCredentialsSet(): void
    {
        self::$cl->setRoleCredentials('myaccountid', 'myroleid', 'mypassword');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_login=myaccountid%21myroleid&s_pw=mypassword&s_command=COMMAND%3DStatusAccount');
    }

    public function testSetRoleCredentialsReset(): void
    {
        self::$cl->setRoleCredentials('', '', '');
        $tmp = self::$cl->getPOSTData(array(
            'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_command=COMMAND%3DStatusAccount');
    }

    public function testLoginCredsOK(): void
    {
        self::$cl->useOTESystem()
                ->setCredentials('test.user', 'test.passw0rd');
        $r = self::$cl->login();
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
        $rec = $r->getRecord(0);
        $this->assertNotNull($rec);
        $this->assertNotNull($rec->getDataByKey('SESSION'));
    }

    /*public function testLoginRoleCredsOK(): void
    {
        self::$cl->setRoleCredentials('test.user', 'testrole', 'test.passw0rd');
        $r = self::$cl->login();
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
        $rec = $r->getRecord(0);
        $this->assertNotNull($rec);
        $this->assertNotNull($rec->getDataByKey('SESSION'));
    }*/

    public function testLoginCredsFAIL(): void
    {
        self::$cl->setCredentials('test.user', 'WRONGPASSWORD');
        $r = self::$cl->login();
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isError(), true);
    }

    //TODO -> not covered: login failed; http timeout
    //TODO -> not covered: login succeeded; no session returned

    public function testLoginExtendedCredsOK(): void
    {
        self::$cl->useOTESystem()
                ->setCredentials('test.user', 'test.passw0rd');
        $r = self::$cl->loginExtended(array(
            "TIMEOUT" => 60
        ));
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
        $rec = $r->getRecord(0);
        $this->assertNotNull($rec);
        $this->assertNotNull($rec->getDataByKey('SESSION'));
    }

    public function testLogoutOK(): void
    {
        $r = self::$cl->logout();
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
    }

    public function testLogoutFAIL(): void
    {
        $r = self::$cl->logout();
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isError(), true);
    }

    public function testRequestFlattenCommand(): void
    {
        self::$cl->setCredentials('test.user', 'test.passw0rd')
                ->useOTESystem();
        $r = self::$cl->request(array( 'COMMAND' => 'CheckDomains', 'DOMAIN' => ['example.com', 'example.net'] ));
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
        $this->assertEquals($r->getCode(), 200);
        $this->assertEquals($r->getDescription(), "Command completed successfully");
        $cmd = $r->getCommand();
        $keys = array_keys($cmd);
        $this->assertEquals(in_array("DOMAIN0", $keys), true);
        $this->assertEquals(in_array("DOMAIN1", $keys), true);
        $this->assertEquals(in_array("DOMAIN", $keys), false);
        $this->assertEquals($cmd["DOMAIN0"], "example.com");
        $this->assertEquals($cmd["DOMAIN1"], "example.net");
    }

    public function testRequestAUTOIdnConvert(): void
    {
        self::$cl->setCredentials('test.user', 'test.passw0rd')
                ->useOTESystem();
        $r = self::$cl->request(array( 'COMMAND' => 'CheckDomains', 'DOMAIN' => ['example.com', 'dömäin.example', 'example.net'] ));
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
        $this->assertEquals($r->getCode(), 200);
        $this->assertEquals($r->getDescription(), "Command completed successfully");
        $cmd = $r->getCommand();
        $keys = array_keys($cmd);
        $this->assertEquals(in_array("DOMAIN0", $keys), true);
        $this->assertEquals(in_array("DOMAIN1", $keys), true);
        $this->assertEquals(in_array("DOMAIN2", $keys), true);
        $this->assertEquals(in_array("DOMAIN", $keys), false);
        $this->assertEquals($cmd["DOMAIN0"], "example.com");
        $this->assertEquals($cmd["DOMAIN1"], "xn--dmin-moa0i.example");
        $this->assertEquals($cmd["DOMAIN2"], "example.net");
    }

    public function testRequestCodeTmpErrorDbg(): void
    {
        self::$cl->enableDebugMode()
                ->setCredentials('test.user', 'test.passw0rd')
                ->useOTESystem();
        $r = self::$cl->request(array( 'COMMAND' => 'GetUserIndex' ));
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
        $this->assertEquals($r->getCode(), 200);
        $this->assertEquals($r->getDescription(), "Command completed successfully");
        //TODO: this response is a tmp error in node-sdk; "httperror" template
    }

    public function testRequestCodeTmpErrorNoDbg(): void
    {
        self::$cl->disableDebugMode();
        $r = self::$cl->request(array( 'COMMAND' => 'GetUserIndex' ));
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
        $this->assertEquals($r->getCode(), 200);
        $this->assertEquals($r->getDescription(), "Command completed successfully");
        //TODO: this response is a tmp error in node-sdk; "httperror" template
    }

    public function testRequestNextResponsePageNoLast(): void
    {
        $r = self::$cl->request(array(
            'COMMAND' => 'QueryDomainList',
            'LIMIT' => 2,
            'FIRST' => 0
        ));
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
        $nr = self::$cl->requestNextResponsePage($r);
        $this->assertInstanceOf(R::class, $nr);
        $this->assertEquals($nr->isSuccess(), true);
        $this->assertEquals($r->getRecordsLimitation(), 2);
        $this->assertEquals($nr->getRecordsLimitation(), 2);
        $this->assertEquals($r->getRecordsCount(), 2);
        $this->assertEquals($nr->getRecordsCount(), 2);
        $this->assertEquals($r->getFirstRecordIndex(), 0);
        $this->assertEquals($r->getLastRecordIndex(), 1);
        $this->assertEquals($nr->getFirstRecordIndex(), 2);
        $this->assertEquals($nr->getLastRecordIndex(), 3);
    }

    public function testRequestNextResponsePageLast(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Parameter LAST in use. Please remove it to avoid issues in requestNextPage.');
        $r = self::$cl->request(array(
            'COMMAND' => 'QueryDomainList',
            'LIMIT' => 2,
            'FIRST' => 0,
            'LAST'  => 1
        ));
        $this->assertInstanceOf(R::class, $r);
        self::$cl->requestNextResponsePage($r);
    }

    public function testRequestNextResponsePageNoFirst(): void
    {
        self::$cl->disableDebugMode();
        $r = self::$cl->request(array(
            'COMMAND' => 'QueryDomainList',
            'LIMIT' => 2
        ));
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
        $nr = self::$cl->requestNextResponsePage($r);
        $this->assertInstanceOf(R::class, $nr);
        $this->assertEquals($nr->isSuccess(), true);
        $this->assertEquals($r->getRecordsLimitation(), 2);
        $this->assertEquals($nr->getRecordsLimitation(), 2);
        $this->assertEquals($r->getRecordsCount(), 2);
        $this->assertEquals($nr->getRecordsCount(), 2);
        $this->assertEquals($r->getFirstRecordIndex(), 0);
        $this->assertEquals($r->getLastRecordIndex(), 1);
        $this->assertEquals($nr->getFirstRecordIndex(), 2);
        $this->assertEquals($nr->getLastRecordIndex(), 3);
    }

    public function testRequestAllResponsePagesOK(): void
    {
        $pages = self::$cl->requestAllResponsePages(array(
            'COMMAND' => 'QueryUserList',
            'FIRST' => 0,
            'LIMIT' => 10
        ));
        $this->assertGreaterThan(0, count($pages));
        foreach ($pages as &$p) {
            $this->assertInstanceOf(R::class, $p);
            $this->assertEquals($p->isSuccess(), true);
        }
    }

    public function testSetUserView(): void
    {
        self::$cl->setUserView('hexotestman.com');
        $r = self::$cl->request(array(
            'COMMAND' => 'GetUserIndex'
        ));
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
    }

    public function testResetUserView(): void
    {
        self::$cl->setUserView();
        $r = self::$cl->request(array(
            'COMMAND' => 'GetUserIndex'
        ));
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
    }

    public function testSetProxy(): void
    {
        self::$cl->setProxy('127.0.0.1');
        $this->assertEquals(self::$cl->getProxy(), '127.0.0.1');
        self::$cl->setProxy('');
    }

    public function testSetReferer(): void
    {
        self::$cl->setReferer('https://www.hexonet.net/');
        $this->assertEquals(self::$cl->getReferer(), 'https://www.hexonet.net/');
        self::$cl->setReferer('');
    }

    public function testUseHighPerformanceConnectionSetup(): void
    {
        self::$cl->useHighPerformanceConnectionSetup();
        $this->assertEquals(self::$cl->getURL(), ISPAPI_CONNECTION_URL_PROXY);
    }

    public function testUseDefaultSetup(): void
    {
        self::$cl->useDefaultConnectionSetup();
        $this->assertEquals(self::$cl->getURL(), ISPAPI_CONNECTION_URL);
    }
}
