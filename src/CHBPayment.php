<?php

/**
 * CHBPayment
 * Credit payment service for CHB bank
 * It's use simple http service, if you
 * want to use card number, cvv, good
 * through by yourself, you have to change
 * to their own FOCCAS API (written in JAVA)
 * library, and might need a portal from JAVA
 * to PHP
 * 
 * TODO: validate macKey, key, MerchantID, TerminalID, MerchantName
 * 
 * @author alk03073135@gmail.com
 * @license MIT
 */

namespace TaiwanPay;

use InvalidArgumentException;

class CHBPayment
{
    /**
     * AUTH_NORMAL
     * 
     * @const
     */
    const AUTH_NORMAL = 0;

    /**
     * AUTH_SMART_PAY
     * 
     * @const
     */
    const AUTH_SMART_PAY = 1;

    /**
     * AUTH_UPOP
     * 
     * @const
     */
    const AUTH_UPOP = 2;

    /**
     * AUTH_TSM_PAGE
     * 
     * @const
     */
    const AUTH_TSM_PAGE = 3;

    /**
     * macKey
     * 
     * @var string
     */
    protected $macKey = '';

    /**
     * key
     * 
     * @var string
     */
    protected $key = '';

    /**
     * merID
     * 
     * @var string
     */
    protected $merID = '';

    /**
     * MerchantID
     * 
     * @var string
     */
    protected $MerchantID = '';

    /**
     * TerminalID
     * 
     * @var string
     */
    protected $TerminalID = '';

    /**
     * MerchantName
     * 
     * @var string
     */
    protected $MerchantName = '';

    /**
     * normalUrl
     * 
     * @var string
     */
    protected $normalUrl = 'https://www.focas-test.fisc.com.tw/FOCAS_WEBPOS/online/';

    /**
     * smartPayUrl
     * 
     * @var string
     */
    protected $smartPayUrl = 'https://www.focas-test.fisc.com.tw/FOCAS_WEBPOS/debit/';

    /**
     * upopUrl
     * 
     * @var string
     */
    protected $upopUrl = 'https://www.focas-test.fisc.com.tw/FOCAS_UPOP/upop/';

    /**
     * tsmPageUrl
     * 
     * @var string
     */
    protected $tsmPageUrl = 'https://www.focas-test.fisc.com.tw/FOCAS_WEBPOS/TSM_PAGE/';

    /**
     * tsmParmUrl
     * 
     * @var string
     */
    protected $tsmParmUrl = 'https://www.focas-test.fisc.com.tw/FOCAS_WEBPOS/TSM_PARM/';

    /**
     * custUrl
     * 
     * @var string
     */
    protected $custUrl = 'https://www.focas-test.fisc.com.tw/FOCAS_WEBPOS/customizeOnline/';

    /**
     * inqueryUrl
     * 
     * @var string
     */
    protected $inqueryUrl = 'https://www.focas-test.fisc.com.tw/FOCAS_WEBPOS/orderInquery/';

    /**
     * debug
     * 
     * @var bool
     */
    protected $debug = true;

    /**
     * timeout
     * default: 300 seconds
     * 
     * @var int
     */
    protected $timeout = 300;

    /**
     * requiredOptions
     * 
     * @var array
     */
    protected $requiredOptions = [
        'macKey', 'key', 'merID', 'MerchantID', 'TerminalID', 'MerchantName'
    ];

    /**
     * construct
     * 
     * @param array $options
     * @param bool $debug
     * @return void
     */
    public function __construct(array $options, bool $debug)
    {
        // check options
        foreach ($this->requiredOptions as $required) {
            if (array_key_exists($required, $options) !== true) {
                throw new InvalidArgumentException('Option key: ' . $required . ' is required');
            }
            if (!is_string($options[$required])) {
                throw new InvalidArgumentException('Value of option: ' . $required . ' must be string');
            }
            $this->$required = $options[$required];
        }
        
        // set live environment
        if ($debug === false) {
            $this->normalUrl = 'https://www.focas.fisc.com.tw/FOCAS_WEBPOS/online/';
            $this->smartPayUrl = 'https://www.focas.fisc.com.tw/FOCAS_WEBPOS/debit/';
            $this->upopUrl = 'https://www.focas.fisc.com.tw/FOCAS_UPOP/upop/';
            $this->tsmPageUrl = 'https://www.focas.fisc.com.tw/FOCAS_WEBPOS/TSM_PAGE/';
            $this->tsmParmUrl = 'https://www.focas.fisc.com.tw/FOCAS_WEBPOS/TSM_PARM/';
            $this->custUrl = 'https://www.focas.fisc.com.tw/FOCAS_WEBPOS/customizeOnline/';
            $this->inqueryUrl = 'https://www.focas.fisc.com.tw/FOCAS_WEBPOS/orderInquery/';
        }
    }

    /**
     * get
     * 
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);

        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], []);
        }
        return false;
    }

    /**
     * set
     * 
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);

        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], [$value]);
        }
        return false;
    }

    /**
     * getMacKey
     *
     * @return mac key
     */
    public function getMacKey()
    {
        return $this->macKey;
    }

    /**
     * getMerID
     *
     * @return string
     */
    public function getMerID()
    {
        return $this->merID;
    }

    /**
     * getMerchantID
     *
     * @return string
     */
    public function getMerchantID()
    {
        return $this->MerchantID;
    }

    /**
     * getTerminalID
     *
     * @return string
     */
    public function getTerminalID()
    {
        return $this->TerminalID;
    }

    /**
     * getMerchantName
     *
     * @return string
     */
    public function getMerchantName()
    {
        return $this->MerchantName;
    }

    /**
     * getUrl
     * 
     * @param integer $type
     * @return string
     */
    public function getUrl(int $type)
    {
        $url = '';
        switch ($type) {
            case 0:
            $url = $this->normalUrl;
            break;
            case 1:
            $url = $this->smartPayUrl;
            break;
            case 2:
            $url = $this->upopUrl;
            break;
            case 3:
            $url = $this->tsmPageUrl;
            break;
            default:
            $url = $this->normalUrl;
        }
        return $url;
    }

    /**
     * getAuthUrl
     * 
     * @param integer $type
     * @return string
     */
    public function getAuthUrl(int $type)
    {
        return $this->getUrl($type);
    }

    /**
     * getInqueryUrl
     * 
     * @return string
     */
    public function getInqueryUrl()
    {
        return $this->inqueryUrl;
    }

    /**
     * getTimeout
     * 
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * setTimeout
     * 
     * @param int $timeout
     * @return void
     */
    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * TODO: amorAuth
     * 
     * @param string $ono order number
     * @param string $type auth type
     * @return bool
     */
    // public function amorAuth(string $ono, string $type)
    // {
    //     return false;
    // }

    /**
     * TODO: cancelAuth
     * 
     * @param string $ono order number
     * @return bool false
     */
    // public function cancelAuth(string $ono)
    // {
    //     return false;
    // }

    /**
     * auth
     * 
     * @param string $ono order number
     * @param integer $amount total purchase amount
     * @param integer $type payment type
     * @param string $resUrl
     * @param integer $createTime order create timestamp
     * @param bool $returnFormString
     * @return mixed string form or array data
     */
    public function auth(string $ono, int $amount, int $type, string $resUrl, int $createTime, bool $returnFormString)
    {
        $reqToken = $this->getReqToken($ono, $amount, date('Ymdhms', $createTime));
        $reqUrl = $this->getAuthUrl($type);
        $localDate = date('Ymd', $createTime);
        $localTime = date('hms', $createTime);
        if ($returnFormString) {
            $formString = <<<FORMSTRING
            <form id="auth" method="POST" action={$reqUrl}>
                <input type=hidden name=merID value={$this->merID}>
                <input type=hidden name=MerchantID value={$this->MerchantID}>
                <input type=hidden name=TerminalID value={$this->TerminalID}>
                <input type=hidden name=MerchantName value={$this->MerchantName}>
                <input type=hidden name=lidm value={$ono}>
                <input type=hidden name=purchAmt value={$amount}>
                <input type=hidden name=LocalDate value={$localDate}>
                <input type=hidden name=LocalTime value={$localTime}>
                <input type=hidden name=reqToken value={$reqToken}>
                <input type=hidden name=enCodeType value=UTF-8>
                <input type=hidden name=isSynchronism value=0>
                <input type=hidden name=lagSelect value=0>
                <input type=hidden name=AuthResURL value={$resUrl}>
            </form>
            <script>
              (function(){
                var form = document.querySelector('#auth') ||               document.getElementById('auth');
                form.submit();
              })();
            </script>
FORMSTRING;
            return $formString;            
        }
        return [
            'reqUrl' => $reqUrl,
            'merID' => $this->merID,
            'MerchantID' => $this->MerchantID,
            'TerminalID' => $this->TerminalID,
            'MerchantName' => $this->MerchantName,
            'customize' => 0,
            'lidm' => $ono,
            'purchAmt' => $amount,
            'CurrencyNote' => "TWD",
            'AutoCap' => 1,
            'PayType' => $type,
            'LocalDate' => $localDate,
            'LocalTime' => $localTime,
            'reqToken' => $reqToken,
            'enCodeType' => "UTF-8",
            'timeoutDate' => date('Ymd', $createTime),
            'timeoutTime' => date('hms', $createTime + $this->timeout),
            'timeoutSecs' => $this->timeout,
            'isSynchronism' => 0,
            'lagSelect' => 0,
            'resUrl' => $resUrl
        ];
    }

    /**
     * search
     * 
     * @param string $ono order number
     * @param integer $amount total purchase amount
     * @param string $resUrl
     * @param bool $returnFormString return form string or return result string
     * @return mixed string form or bool
     */
    public function search(string $ono, int $amount, string $resUrl, bool $returnFormString)
    {
        if ($returnFormString) {
            $formString = <<<FORMSTRING
            <form id=search action={$this->inqueryUrl}>
                <input type=hidden name=MerchantID value={$this->MerchantID}>
                <input type=hidden name=TerminalID value={$this->TerminalID}>
                <input type=hidden name=merID value={$this->merID}>
                <input type=hidden name=purchAmt value={$amount}>
                <input type=hidden name=lidm value={$ono}>
                <input type=hidden name=ResURL value={$resUrl}>
            </form>
            <script>
              (function(){
                var form = document.querySelector('#search') ||               document.getElementById('search');
                form.submit();
              })();
            </script>
FORMSTRING;
            return $formString;
        }
        return [
            'reqUrl' => $this->inqueryUrl,
            'MerchantID' => $this->MerchantID,
            'TerminalID' => $this->TerminalID,
            'merID' => $this->merID,
            'purchAmt' => $amount,
            'lidm' => $ono,
            'resUrl' => $resUrl
        ];
    }

    /**
     * getRequestToken
     * 
     * @param string $lidm order number
     * @param integer $amount total purchase amount
     * @param string $date datetime format in Ymdhms
     * @return string token
     */
    public function getReqToken($lidm, $amount, $date)
    {
        $mac = sprintf("%s&%s&%s&%s&%s&%s", $lidm, $amount, $this->key, $this->MerchantID, $this->TerminalID, date('Ymdhms', $date));
        return hash('sha256', $mac);
    }
}
