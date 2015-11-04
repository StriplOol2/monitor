<?php

namespace VLru\ApiBundle\Tests\Params;

use Symfony\Component\HttpFoundation\Request;
use VLru\ApiBundle\Configuration\Params\DatesRange;
use VLru\ApiBundle\Request\DatesRangeParam;
use VLru\ApiBundle\Request\Params\ParamsValidationException;

/**
 * @fast
 * @kernel
 * @covers VLru\ApiBundle\Configuration\Params\DatesRange
 * @covers VLru\ApiBundle\Configuration\Params\DatesRangeTransformer
 */
class DatesRangeTest extends BaseParamsTestCase
{
    public function parsingData()
    {
        return [
             'year' => [
                 '/test?year=2010',
                 '2010-01-01 00:00:00',
                 '2010-12-31 23:59:59'
             ],
             'year&month' => [
                 '/test?year=2010&month=12',
                 '2010-12-01 00:00:00',
                 '2010-12-31 23:59:59'
             ],
             'date_start' => [
                 '/test?date_start='.strtotime('2010-12-01 00:00:00'),
                 '2010-12-01 00:00:00',
                 null
             ],
             'date_end' => [
                 '/test?date_end='.strtotime('2010-12-01 00:00:00'),
                 null,
                 '2010-12-01 00:00:00'
             ],
             'date_start&date_end' => [
                 '/test?date_start='.strtotime('2010-11-01 00:00:00').'&date_end='.strtotime('2010-12-01 00:00:00'),
                 '2010-11-01 00:00:00',
                 '2010-12-01 00:00:00'
             ],
        ];
    }

    /**
     * @dataProvider parsingData
     * @param $url
     * @param $expectedStart
     * @param $expectedEnd
     */
    public function testParsing($url, $expectedStart, $expectedEnd)
    {
        $request = Request::create($url);
        $param = new DatesRange(['name' => 'param']);

        $parsedData = $this->parseRequest($request, $param);

        $expectedParam = new DatesRangeParam(
            null !== $expectedStart ? new \DateTime($expectedStart) : null,
            null !== $expectedEnd ? new \DateTime($expectedEnd) : null
        );
        $this->assertEquals(['param' => $expectedParam], $parsedData);
    }

    public function testNull()
    {
        $request = Request::create('/test');
        $param = new DatesRange(['name' => 'param']);

        $parsedData = $this->parseRequest($request, $param);

        $this->assertEquals(['param' => null], $parsedData);
    }

    public function badRequestData()
    {
        return [
            'year' => ['/test?year=asd'],
            'month' => ['/test?year=2000&month=13'],
            'date_start' => ['/test?date_start=asdasd'],
            'date_end' => ['/test?date_end=-124']
        ];
    }

    /**
     * @dataProvider badRequestData
     * @param $url
     */
    public function testBadRequest($url)
    {
        $request = Request::create($url);
        $param = new DatesRange(['name' => 'param']);

        $this->setExpectedException(ParamsValidationException::class);
        $this->parseRequest($request, $param);
    }
}
