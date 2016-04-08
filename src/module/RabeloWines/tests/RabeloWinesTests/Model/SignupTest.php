<?php

namespace RabeloWinesTests\Model;

use RabeloWines\Model\Signup;
use Zend\InputFilter\InputFilter;
use PHPUnit_Framework_TestCase;

class SignupTest extends PHPUnit_Framework_TestCase
{
    public function testSignupInitialState()
    {
        $signup = new Signup();

        $this->assertNull(
            $signup->email,
            '"email" should initially be null'
        );
    }

    /**
     * @expectedException Zend\InputFilter\Exception\RuntimeException
     * @expectedExceptionMessage Not used
     */
    public function testSignupCannotSetInputFilter()
    {
        $signup = new Signup();
        $signup->setInputFilter(new InputFilter());
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $signup = new Signup();
        $data  = array('email' => 'noreply@rabelowines.com');

        $signup->exchangeArray($data);

        $this->assertSame(
            $data['email'],
            $signup->email,
            '"email" was not set correctly'
        );
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $signup = new Signup();

        $signup->exchangeArray(array('email' => 'noreply@rabelowines.com'));
        $signup->exchangeArray(array());

        $this->assertNull(
            $signup->email, '"email" should have defaulted to null'
        );
    }

    public function testInputFiltersAreSetCorrectly()
    {
        $signup = new Signup();

        $inputFilter = $signup->getInputFilter();

        $this->assertSame(2, $inputFilter->count());
        $this->assertTrue($inputFilter->has('email'));
    }
}