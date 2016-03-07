<?php
/**
 * YAWIK
 * 
 * @filesource
 * @copyright (c) 2013-2015 Cross Solution (http://cross-solution.de)
 * @license   MIT
 * @author    weitz@cross-solution.de
 */

namespace YawikXingVendorApi\Options;

use YawikXingVendorApi\Options\ModuleOptions as Options;

/**
 *
 * @covers \YawikXingVendorApi\Options\ModuleOptions
 * @coversDefaultClass \YawikXingVendorApi\Options\ModuleOptions
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo   write test
 */
class ModuleOptionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Options $options
     */
    protected $options;

    public function setUp()
    {
        $options = new Options;
        $this->options = $options;
    }

    /**
     * @testdox Extends \Zend\Stdlib\AbstractOptions
     * @coversNothing
     */
    public function testExtendsAbstractOptions()
    {
        $this->assertInstanceOf('\Zend\Stdlib\AbstractOptions', $this->options);
    }

    public function stringValuesProvider()
    {
        return [
            [ 'AuthorizedUser', 'testUser', null ],
            [ 'AuthorizedUser', 1234, null ],
            [ 'OrderIdKey', 'testOrderIdKey', 'xingOrderId' ],
            [ 'organizationId', 'testOrganizationId', null ],
        ];
    }

    /**
     * @testdox Allows setting "authorized_user", "order_id_key" and "organization_id" as string values
     * @dataProvider stringValuesProvider
     *
     * @param string $method
     * @param string $value
     * @param string $default
     */
    public function testStringSetterAndGetter($method, $value, $default)
    {
        $getter = "get$method";
        $setter = "set$method";

        $this->assertEquals($default, $this->options->$getter(), 'Wrong default value!');
        $this->assertSame($this->options, $this->options->$setter($value), 'Fluent interface broken.');
        $this->assertEquals($value, $this->options->$getter());
    }

    public function booleanValuesProvider()
    {
        return [
            [ 'ApiPreview', true ]
        ];
    }

    /**
     * @testdox Allows setting "api_preview" as boolean value.
     * @dataProvider booleanValuesProvider
     *
     * @param string $method
     * @param boolean $default
     */
    public function testBooleanSetterAndGetter($method, $default)
    {
        $setter = "set$method";
        $getter = "get$method";

        $default
        ? $this->assertTrue($this->options->$getter(), 'Wrong default value!')
        : $this->assertFalse($this->options->$getter(), 'Wrong default value!');

        /* explicit value setting */
        $this->assertSame($this->options, $this->options->$setter(true), 'Fluent interface broken.');
        $this->assertTrue($this->options->$getter(), 'Explicit setting true failed.');
        $this->options->$setter(false);
        $this->assertFalse($this->options->$getter(), 'Explicit setting false failed.');

        /* Implicit setting true */
        foreach ([ "notEmptyString", new \stdClass(), 1234, ['not empty', 'array']] as $value) {
            $this->options->$setter($value);
            $this->assertTrue($this->options->$getter(), 'Implicit setting true with ' . var_export($value, true) . ' failed.');
        }

        /* Implicit setting false */
        foreach ([ "0", "", null, 0, []] as $value) {
            $this->options->$setter($value);
            $this->assertFalse($this->options->$getter(), 'Implicit setting false with ' . var_export($value, true) . ' failed.');
        }

    }

    /**
     * @testdox Allows setting the "log_level".
     */
    public function testSetGetLoglevel()
    {
        $this->assertFalse($this->options->getLogLevel());
        $this->options->setLogLevel(2);
        $this->assertEquals(2, $this->options->getLogLevel());
        $this->options->setLogLevel('2');
        $this->assertEquals(2, $this->options->getLogLevel());
        $this->options->setLogLevel(18);
        $this->assertEquals(7, $this->options->getLogLevel());
        $this->options->setLogLevel('not2Num');
        $this->assertFalse($this->options->getLogLevel());
    }

    /**
     * @testdox Allows setting multiple "order_ids".
     */
    public function testSetAndGetOrderIds()
    {
        $testData = ['DEFAULT' => 1234, 'OTHER' => '4321'];
        $this->assertEquals(['DEFAULT' => 968180], $this->options->getOrderIds(), 'Wrong default value');
        $this->assertSame($this->options, $this->options->setOrderIds($testData), 'Fluent interface broken');
        $this->assertEquals($testData, $this->options->getOrderIds(), 'Setting order ids as array failed.');

        $this->assertSame($this->options, $this->options->setOrderId(4567), 'Fluent interface on setOrderId broken.');
        $this->assertEquals(array_merge($testData, ['DEFAULT' => 4567]), $this->options->getOrderIds(), 'Setting default order id through setter failed.');

        $testData = [ 'DEFAULT' => 4567, 'OTHER' => '4321', 'ADD' => 7654 ];
        $this->options->setOrderId(7654, 'ADD');

        $this->assertEquals(4567, $this->options->getOrderId());
        $this->assertEquals(4321, $this->options->getOrderId('OTHER'));
        $this->assertEquals(7654, $this->options->getOrderId('ADD'));
        $this->assertEquals($testData, $this->options->getOrderIds());
    }
}
