<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use HEXONET\APIClient as CL;
use HEXONET\Response as R;

final class APIClientTest extends TestCase
{
    public static $cl;

    public static function setUpBeforeClass() {
        session_start();
        self::$cl = new CL();
    }

    public static function tearDownAfterClass() {
        self::$cl = null;
        session_destroy();
    }

    public function test_getPOSTDataObj() {
        $enc = self::$cl->getPOSTData(array(
            'COMMAND' => 'ModifyDomain',
            'AUTH' => 'gwrgwqg%&\\44t3*'
        ));
        $this->assertEquals($enc, 's_entity=54cd&s_command=COMMAND%3DModifyDomain%0AAUTH%3Dgwrgwqg%25%26%5C44t3%2A');
    }

    public function test_getPOSTDataStr() {
        $enc = self::$cl->getPOSTData('gregergege');
        $this->assertEquals($enc, 's_entity=54cd&s_command=');
    }

    public function test_getPOSTDataNull() {
      $enc = self::$cl->getPOSTData(array(
        'COMMAND' => 'ModifyDomain',
        'AUTH' => null
      ));
      $this->assertEquals($enc, 's_entity=54cd&s_command=COMMAND%3DModifyDomain');
    }

    public function test_enableDebugMode() {
        self::$cl->enableDebugMode();
    }

    public function test_disableDebugMode() {
        self::$cl->disableDebugMode();
    }

    public function test_getURL() {
        $url = self::$cl->getURL();
        $this->assertEquals($url, 'https://coreapi.1api.net/api/call.cgi');
    }

    public function test_setURL() {
        $url = self::$cl->setURL('http://coreapi.1api.net/api/call.cgi')->getURL();
        $this->assertEquals($url, 'http://coreapi.1api.net/api/call.cgi');
        self::$cl->setURL('https://coreapi.1api.net/api/call.cgi');
    }

    public function test_setOTPSet() {
        self::$cl->setOTP('12345678');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_otp=12345678&s_command=COMMAND%3DStatusAccount');
    }

    public function test_setOTPReset() {
        self::$cl->setOTP('');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_command=COMMAND%3DStatusAccount');
    }

    public function test_setSessionSet() {
        self::$cl->setSession('12345678');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_session=12345678&s_command=COMMAND%3DStatusAccount');
    }

    public function test_setSessionCredentials() {
        // credentials and otp code have to be unset when session id is set
        self::$cl->setRoleCredentials('myaccountid', 'myrole', 'mypassword')
                ->setOTP('12345678')
                ->setSession('12345678');
        $tmp = self::$cl->getPOSTData(array(
            'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_session=12345678&s_command=COMMAND%3DStatusAccount');
    }

    public function test_setSessionReset() {
        self::$cl->setSession('');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_command=COMMAND%3DStatusAccount');
    }

    public function test_saveReuseSession() {
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

    public function test_setRemoteIPAddressSet() {
        self::$cl->setRemoteIPAddress('10.10.10.10');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_remoteaddr=10.10.10.10&s_command=COMMAND%3DStatusAccount');
    }

    public function test_setRemoteIPAddressReset() {
        self::$cl->setRemoteIPAddress('');
        $tmp = self::$cl->getPOSTData(array(
            'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_command=COMMAND%3DStatusAccount');
    }

    public function test_setCredentialsSet() {
        self::$cl->setCredentials('myaccountid', 'mypassword');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_login=myaccountid&s_pw=mypassword&s_command=COMMAND%3DStatusAccount');
    }

    public function test_setCredentialsReset() {
        self::$cl->setCredentials('', '');
        $tmp = self::$cl->getPOSTData(array(
            'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_command=COMMAND%3DStatusAccount');
    }

    public function test_setRoleCredentialsSet() {
        self::$cl->setRoleCredentials('myaccountid', 'myroleid', 'mypassword');
        $tmp = self::$cl->getPOSTData(array(
          'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_login=myaccountid%21myroleid&s_pw=mypassword&s_command=COMMAND%3DStatusAccount');
    }

    public function test_setRoleCredentialsReset() {
        self::$cl->setRoleCredentials('', '', '');
        $tmp = self::$cl->getPOSTData(array(
            'COMMAND' => 'StatusAccount'
        ));
        $this->assertEquals($tmp, 's_entity=54cd&s_command=COMMAND%3DStatusAccount');
    }

    public function test_loginCredsOK() {
        self::$cl->useOTESystem()
                ->setCredentials('test.user', 'test.passw0rd');
        $r = self::$cl->login();
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
        $rec = $r->getRecord(0);
        $this->assertNotNull($rec);
        $this->assertNotNull($rec->getDataByKey('SESSION'));
    }

    public function test_loginRoleCredsOK() {
        self::$cl->setRoleCredentials('test.user', 'testrole', 'test.passw0rd');
        $r = self::$cl->login();
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
        $rec = $r->getRecord(0);
        $this->assertNotNull($rec);
        $this->assertNotNull($rec->getDataByKey('SESSION'));
    }

    public function test_loginCredsFAIL() {
        self::$cl->setCredentials('test.user', 'WRONGPASSWORD');
        $r = self::$cl->login();
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isError(), true);
    }

    //TODO -> not covered: login failed; http timeout
    //TODO -> not covered: login succeeded; no session returned

    public function test_loginExtendedCredsOK() {
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

    public function test_logoutOK() {
        $r = self::$cl->logout();
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
    }

    public function test_logoutFAIL() {
        $r = self::$cl->logout();
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isError(), true);
    }

    public function test_requestCodeTmpErrorDbg() {
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

    public function test_requestCodeTmpErrorNoDbg() {
        self::$cl->disableDebugMode();
        $r = self::$cl->request(array( 'COMMAND' => 'GetUserIndex' ));
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
        $this->assertEquals($r->getCode(), 200);
        $this->assertEquals($r->getDescription(), "Command completed successfully");
        //TODO: this response is a tmp error in node-sdk; "httperror" template
    }

    public function test_requestNextResponsePageNoLast() {
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

    public function test_requestNextResponsePageLast() {
        $this->expectException(Error::class);
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

    public function test_requestNextResponsePageNoFirst() {
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

    public function test_requestAllResponsePagesOK() {
        $pages = self::$cl->requestAllResponsePages(array(
            'COMMAND' => 'QueryUserList',
            'FIRST' => 0,
            'LIMIT' => 10
        ));
        $this->assertGreaterThan(0, count($pages));
        foreach($pages as &$p) {
            $this->assertInstanceOf(R::class, $p);
            $this->assertEquals($p->isSuccess(), true);
        }
    }

    public function test_setUserView() {
        self::$cl->setUserView('hexotestman.com');
        $r = self::$cl->request(array(
            'COMMAND' => 'GetUserIndex'
        ));
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
    }

    public function test_resetUserView() {
        self::$cl->setUserView();
        $r = self::$cl->request(array(
            'COMMAND' => 'GetUserIndex'
        ));
        $this->assertInstanceOf(R::class, $r);
        $this->assertEquals($r->isSuccess(), true);
    }
}