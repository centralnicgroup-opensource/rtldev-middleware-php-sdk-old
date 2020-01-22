<?php
declare(strict_types=1);

namespace HEXONETTEST;

use PHPUnit\Framework\TestCase;
use HEXONET\APIClient as CL;
use HEXONET\Response as R;

final class APIClientTest extends TestCase
{
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

    public function testGetPOSTDataObj()
    {
        $enc = self::$cl->getPOSTData(array(
            'COMMAND' => 'ModifyDomain',
            'AUTH' => 'gwrgwqg%&\\44t3*'
        ));
        $this->assertEquals($enc, 's_entity=54cd&s_command=COMMAND%3DModifyDomain%0AAUTH%3Dgwrgwqg%25%26%5C44t3%2A');
    }

    public function testGetPOSTDataStr()
    {
        $enc = self::$cl->getPOSTData('gregergege');
        $this->assertEquals($enc, 's_entity=54cd&s_command=');
    }

    public function testGetPOSTDataNull()
    {
        $enc = self::$cl->getPOSTData(array(
        'COMMAND' => 'ModifyDomain',
        'AUTH' => null
        ));
        $this->assertEquals($enc, 's_entity=54cd&s_command=COMMAND%3DModifyDomain');
    }

    public function testEnableDebugMode()
    {
        self::$cl->enableDebugMode();
        $this->assertEquals(1, 1);//suppress warning for risky test
    }

    public function testDisableDebugMode()
    {
        self::$cl->disableDebugMode();
        $this->assertEquals(1, 1);//suppress warning for risky test
    }

    public function testGetSession()
    {
        $sessid = self::$cl->getSession();
        $this->assertNull($sessid);
    }

    public function testGetSessionIDSet()
    {
        $sess = 'testsession12345';
        $sessid = self::$cl->setSession($sess)->getSession();
        $this->assertEquals($sessid, $sess);
        self::$cl->setSession('');
    }

    public function testGetURL()
    {
        $url = self::$cl->getURL();
        $this->assertEquals($url, 'https://api.ispapi.net/api/call.cgi');
    }

    public function testGetUserAgent()
    {
        $ua = "PHP-SDK (". PHP_OS . "; ". php_uname('m') . "; rv:" . self::$cl->getVersion() . ") php/" . PHP_VERSION;
        $this->assertEquals(self::$cl->getUserAgent(), $ua);
    }

    public function testSetUserAgent()
    {
        $pid = "WHMCS";
        $rv = "7.7.0";
        $ua = $pid . " (". PHP_OS . "; ". php_uname('m') . "; rv:" . $rv . ") php-sdk/" . self::$cl->getVersion() . " php/" . PHP_VERSION;
        $cls = self::$cl->setUserAgent($pid, $rv);
        $this->assertInstanceOf(CL::class, $cls);
        $this->assertEquals(self::$cl->getUserAgent(), $ua);
    }

    public function testSetURL()
    {
        $url = self::$cl->setURL('http://api.ispapi.net/api/call.cgi')->getURL();
        $this->assertEquals($url, 'http://api.ispapi.net/api/call.cgi');
        self::$cl->setURL('https://api.ispapi.net/api/call.cgi');
    }

    public function testSetOTPSet()
    {
        self::$cl->setOTP('12345678');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_otp=12345678&s_command=COMMAND%3DStatusAccount');
    }

    public function testSetOTPReset()
    {
        self::$cl->setOTP('');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_command=COMMAND%3DStatusAccount');
    }

    public function testSetSessionSet()
    {
        self::$cl->setSession('12345678');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_session=12345678&s_command=COMMAND%3DStatusAccount');
    }

    public function testSetSessionCredentials()
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

    public function testSetSessionReset()
    {
        self::$cl->setSession('');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_command=COMMAND%3DStatusAccount');
    }

    public function testSaveReuseSession()
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

    public function testSetRemoteIPAddressSet()
    {
        self::$cl->setRemoteIPAddress('10.10.10.10');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_remoteaddr=10.10.10.10&s_command=COMMAND%3DStatusAccount');
    }

    public function testSetRemoteIPAddressReset()
    {
        self::$cl->setRemoteIPAddress('');
        $tmp = self::$cl->getPOSTData(array(
            'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_command=COMMAND%3DStatusAccount');
    }

    public function testSetCredentialsSet()
    {
        self::$cl->setCredentials('myaccountid', 'mypassword');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_login=myaccountid&s_pw=mypassword&s_command=COMMAND%3DStatusAccount');
    }

    public function testSetCredentialsReset()
    {
        self::$cl->setCredentials('', '');
        $tmp = self::$cl->getPOSTData(array(
            'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_command=COMMAND%3DStatusAccount');
    }

    public function testSetRoleCredentialsSet()
    {
        self::$cl->setRoleCredentials('myaccountid', 'myroleid', 'mypassword');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_login=myaccountid%21myroleid&s_pw=mypassword&s_command=COMMAND%3DStatusAccount');
    }

    public function testSetRoleCredentialsReset()
    {
        self::$cl->setRoleCredentials('', '', '');
        $tmp = self::$cl->getPOSTData(array(
            'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_command=COMMAND%3DStatusAccount');
    }

    public function testLoginCredsOK()
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

    public function testLoginRoleCredsOK()
    {
        self::$cl->setRoleCredentials('test.user', 'testrole', 'test.passw0rd');
        $r = self::$cl->login();
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
        $rec = $r->getRecord(0);
        $this->assertNotNull($rec);
        $this->assertNotNull($rec->getDataByKey('SESSION'));
    }

    public function testLoginCredsFAIL()
    {
        self::$cl->setCredentials('test.user', 'WRONGPASSWORD');
        $r = self::$cl->login();
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isError(), true);
    }

    //TODO -> not covered: login failed; http timeout
    //TODO -> not covered: login succeeded; no session returned

    public function testLoginExtendedCredsOK()
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

    public function testLogoutOK()
    {
        $r = self::$cl->logout();
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
    }

    public function testLogoutFAIL()
    {
        $r = self::$cl->logout();
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isError(), true);
    }

    public function testRequestCodeTmpErrorDbg()
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

    public function testRequestCodeTmpErrorNoDbg()
    {
        self::$cl->disableDebugMode();
        $r = self::$cl->request(array( 'COMMAND' => 'GetUserIndex' ));
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
        $this->assertEquals($r->getCode(), 200);
        $this->assertEquals($r->getDescription(), "Command completed successfully");
        //TODO: this response is a tmp error in node-sdk; "httperror" template
    }

    public function testRequestNextResponsePageNoLast()
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

    public function testRequestNextResponsePageLast()
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

    public function testRequestNextResponsePageNoFirst()
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

    public function testRequestAllResponsePagesOK()
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

    public function testSetUserView()
    {
        self::$cl->setUserView('hexotestman.com');
        $r = self::$cl->request(array(
            'COMMAND' => 'GetUserIndex'
        ));
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
    }

    public function testResetUserView()
    {
        self::$cl->setUserView();
        $r = self::$cl->request(array(
            'COMMAND' => 'GetUserIndex'
        ));
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
    }
}
