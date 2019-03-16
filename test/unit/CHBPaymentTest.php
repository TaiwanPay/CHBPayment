<?php

namespace Test\Unit;

use Test\TestCase;
use TaiwanPay\CHBPayment;

class CHBPaymentTest extends TestCase
{
    /**
     * testAuth
     * 
     * @return void
     */
    public function testAuth()
    {
        $macKey = $this->randomString(32, $this->sourceNumber . $this->sourceUpperAlphabet);
        $key = $this->randomString(15, $this->sourceNumber . $this->sourceLowerAlphabet . $this->sourceUpperAlphabet);
        $merID = $this->randomString(8, $this->sourceNumber);
        $MerchantID = $this->randomString(15, $this->sourceNumber);
        $TerminalID = $this->randomString(8, $this->sourceNumber);
        $MerchantName = $this->randomString(10, $this->sourceNumber . $this->sourceUpperAlphabet);
        $payment = new CHBPayment([
            'macKey' => $macKey,
            'key' => $key,
            'merID' => $merID,
            'MerchantID' => $MerchantID,
            'TerminalID' => $TerminalID,
            'MerchantName' => $MerchantName
        ], false);
        $orderNumber = $this->randomString(10, $this->sourceNumber . $this->sourceUpperAlphabet);
        $amount = 100;
        $type = $payment::AUTH_NORMAL;
        $resUrl = 'http://localhost/test';
        $createTime = time();
        $reqToken = $payment->getReqToken($orderNumber, $amount, date('Ymdhms', $createTime));
        $reqUrl = $payment->getAuthUrl($type);
        $auth = $payment->auth($orderNumber, $amount, $type, $resUrl, $createTime, false);
        $this->assertEquals($reqUrl, $auth['reqUrl']);
        $this->assertEquals($merID, $auth['merID']);
        $this->assertEquals($MerchantID, $auth['MerchantID']);
        $this->assertEquals($TerminalID, $auth['TerminalID']);
        $this->assertEquals($MerchantName, $auth['MerchantName']);
        $this->assertEquals($orderNumber, $auth['lidm']);
        $this->assertEquals($amount, $auth['purchAmt']);
        $this->assertEquals($type, $auth['PayType']);
        $this->assertEquals($reqToken, $auth['reqToken']);
        $this->assertEquals($resUrl, $auth['resUrl']);
        $this->assertEquals(date('Ymd', $createTime), $auth['LocalDate']);
        $this->assertEquals(date('hms', $createTime), $auth['LocalTime']);
        $this->assertEquals('TWD', $auth['CurrencyNote']);
        $formAuth = $payment->auth($orderNumber, $amount, $type, $resUrl, $createTime, true);
        $this->assertTrue(is_string($formAuth));
    }

    /**
     * testSearch
     * 
     * @return void
     */
    public function testSearch()
    {
        $macKey = $this->randomString(32, $this->sourceNumber . $this->sourceUpperAlphabet);
        $key = $this->randomString(15, $this->sourceNumber . $this->sourceLowerAlphabet . $this->sourceUpperAlphabet);
        $merID = $this->randomString(8, $this->sourceNumber);
        $MerchantID = $this->randomString(15, $this->sourceNumber);
        $TerminalID = $this->randomString(8, $this->sourceNumber);
        $MerchantName = $this->randomString(10, $this->sourceNumber . $this->sourceUpperAlphabet);
        $payment = new CHBPayment([
            'macKey' => $macKey,
            'key' => $key,
            'merID' => $merID,
            'MerchantID' => $MerchantID,
            'TerminalID' => $TerminalID,
            'MerchantName' => $MerchantName
        ], false);
        $orderNumber = $this->randomString(10, $this->sourceNumber . $this->sourceUpperAlphabet);
        $amount = 100;
        $resUrl = 'http://localhost/test';
        $reqUrl = $payment->inqueryUrl;
        $search = $payment->search($orderNumber, $amount, $resUrl, false);
        $this->assertEquals($reqUrl, $search['reqUrl']);
        $this->assertEquals($merID, $search['merID']);
        $this->assertEquals($MerchantID, $search['MerchantID']);
        $this->assertEquals($TerminalID, $search['TerminalID']);
        $this->assertEquals($orderNumber, $search['lidm']);
        $this->assertEquals($amount, $search['purchAmt']);
        $this->assertEquals($resUrl, $search['resUrl']);
        $formSearch = $payment->search($orderNumber, $amount, $resUrl, true);
        $this->assertTrue(is_string($formSearch));
    }
}
