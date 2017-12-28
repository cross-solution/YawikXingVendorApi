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

use Jobs\Entity\Job;
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


    public function testSetJobTitleInXingData()
    {
        $job = new Job();
        $job->setTitle('Test%Job');

        $xingData = new XingData();
        $xingFilterData = new XingFilterData($xingData, null, [], $job);

        $this->filter->filter($xingFilterData);

        $this->assertEquals('Test\u0025Job', $xingData->getFunction());
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
        $job = new Job();
        $job->setTitle('TestJobTitle');

        $xingData = new XingData();
        $xingData->setDescription('<p style="test">foobar</p>');
        $xingFilterData1 = new XingFilterData($xingData, null, [] ,$job, null);

        $xingData = new XingData();
        $xingData->setDescription('<p style="test">foo % bar</p> replace % by entity');
        $xingFilterData2 = new XingFilterData($xingData, null, [] ,$job, null);


        return array(
            [$xingFilterData1,'<p>foobar</p>'],
            [$xingFilterData2,'<p>foo \u0025 bar</p> replace \u0025 by entity'],
        );
    }
}
