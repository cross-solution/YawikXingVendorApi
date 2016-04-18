<?php
/**
 * YAWIK
 *
 * @filesource
 * @license    MIT
 * @copyright  2013 - 2016 Cross Solution <http://cross-solution.de>
 */

/** */
namespace YawikXingVendorApiTest\Filter\XingData;

use YawikXingVendorApi\Entity\XingData;
use YawikXingVendorApi\Filter\XingData\Description;
use YawikXingVendorApi\Filter\XingFilterData;

/**
 * TestCase for \Auth\Filter\StripQueryParams.
 * @coversDefaultClass \Auth\Filter\StripQueryParams
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @group  Auth
 * @group  Auth.Filter
 */
class DescriptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Fixture of the class under test
     *
     * @var Description
     */
    public $filter;

    /**
     * Setups shared fixture
     */
    public function setup()
    {
        $this->filter = new Description();
    }

    /**
     * Tests if filter implements the FilterInterface.
     *
     * @coversNothing
     */
    public function testImplementsZfFilterInterface()
    {
        $this->assertInstanceOf('\Zend\Filter\FilterInterface', $this->filter);
    }


    /**
     * Tests if attributes are stripped.
     *
     * @dataProvider provideHtml
     *
     * @covers ::setStripParams
     * @covers ::getStripParams
     */
    public function testDescriptionWithAttributesInTags($input, $expected)
    {
        $this->filter->filter($input);

        $this->assertEquals($expected, $input->getXingData()->getDescription());
    }


    /**
     * Data provider for testStripsQueryParametersFromUrlStrings
     *
     * @return array
     */
    public function provideHtml()
    {

        $xingData = new XingData();
        $xingData->setDescription('<p style="test">foobar</p>');



        $xingFilterData = new XingFilterData($xingData, null, [] ,null, null);


        return array(
            [$xingFilterData,'<p>foobar</p>']
        );
    }
}