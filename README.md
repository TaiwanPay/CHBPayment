# CHBPayment
[![Build Status](https://travis-ci.org/TaiwanPay/CHBPayment.svg?branch=master)](https://travis-ci.org/TaiwanPay/CHBPayment)
[![codecov](https://codecov.io/gh/TaiwanPay/CHBPayment/branch/master/graph/badge.svg)](https://codecov.io/gh/TaiwanPay/CHBPayment)

Payment library for CHB bank in Taiwan. It use simply web api for CHB bank.

# Install
```BASH
$ composer require taiwan-pay/chb-payment
```

# Usage
* initiate the payment
```PHP
use TaiwanPay\CHBPayment;

$payment = new CHBPayment([
    'macKey' => $macKey,
    'key' => $key,
    'merID' => $merID,
    'MerchantID' => $MerchantID,
    'TerminalID' => $TerminalID,
    'MerchantName' => $MerchantName
], false);
```

* authenticate an order
```PHP
// get auth data and render form by yourself
$payment->auth($orderNumber, $amount, $type, $resUrl, $createTime, false);

// get auth form and render it
$payment->auth($orderNumber, $amount, $type, $resUrl, $createTime, true);
```

* search an order
```PHP
// get search data and render form by yourself
$payment->search($orderNumber, $amount, $resUrl, false);

// get search form and render it
$payment->search($orderNumber, $amount, $resUrl, true);
```

> Note: All the response will be `POST` to `$resUrl` from bank.

# License
MIT
