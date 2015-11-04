<?php

namespace VLru\ApiBundle\Tests\VersionValidation;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiVersionValidationTest extends WebTestCase
{
    public function testDataProvider()
    {
        return [
            'to' => ['1.0', 1],
            'edge' => ['1.5', 1],
            'to and from major' => ['1.8', 2],
            'to and from minor' => ['2.2', 2],
            'exact' => ['2.6', 3],
            'not found' => ['2.8', 0],
            'to and from same major' => ['3.1', 4],
            'from' => ['3.4', 5],
            'from major' => ['4.3', 5],
            'bad version 1' => ['2.asd', 0],
            'bad version 2' => ['-1.2', 0],
            'bad version 3' => ['12', 0],
        ];
    }

    /**
     * @dataProvider testDataProvider
     *
     * @param string $version
     * @param int $expectedAction
     */
    public function testVersions($version, $expectedAction)
    {
        if (0 === $expectedAction) {
            $this->setExpectedException(NotFoundHttpException::class);
        }

        $client = $this->createClient();
        $client->request('GET', "/${version}/api-version-test");
        $resp = $client->getResponse();

        if (0 !== $expectedAction) {
            $this->assertEquals("\"action${expectedAction}\"", $resp->getContent());
        }
    }
}
