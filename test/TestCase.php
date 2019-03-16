<?php

namespace Test;

use \PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * sourceNumber
     * 
     * @var string
     */
    protected $sourceNumber = '0123456789';

    /**
     * sourceLowerAlphabet
     * 
     * @var string
     */
    protected $sourceLowerAlphabet = 'abcdefghijklmnopqrstuvwxyz';

    /**
     * sourceUpperAlphabet
     * 
     * @var string
     */
    protected $sourceUpperAlphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * setUp
     * 
     * @return void
     */
    // public function setUp() {}

    /**
     * tearDown
     * 
     * @return void
     */
    // public function tearDown() {}

    /**
     * randomString
     * 
     * @param int $length
     * @param string $source
     * @return string
     */
    protected function randomString(int $length, string $source)
    {
        $sourceLength = strlen($source);
        $random = 0;
        $result = '';
        for ($i=0; $i<$length; $i++) {
            $random = rand(0, $sourceLength);
            $result .= $source[$random];
        }
        return $result;
    }
}