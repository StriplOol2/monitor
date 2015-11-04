<?php

namespace VLru\ApiBundle\Tests\JsonTransform;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use VLru\ApiBundle\EventListener\JsonRequestTransformerListener;

/**
 * @group fast
 * @covers VLru\ApiBundle\EventListener\JsonRequestTransformerListener
 */
class JsonRequestTransformerListenerTest extends \PHPUnit_Framework_TestCase
{
    /** @var LoggerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $logger;

    /** @var JsonRequestTransformerListener */
    protected $listener;

    protected function setUp()
    {
        parent::setUp();

        $this->logger = $this->getMock(LoggerInterface::class);
        $this->listener = new JsonRequestTransformerListener($this->logger);
    }

    public function contentTypeData()
    {
        return [
            'json' => ['application/json', true],
            'x-json' => ['application/x-json', true],
            'from' => ['application/x-www-form-urlencoded', false],
            'html' => ['text/html', false],
            'special' => ['text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8', false],
        ];
    }

    /**
     * @dataProvider contentTypeData
     * @param string $type
     * @param boolean $isJson
     */
    public function testContentType($type, $isJson)
    {
        $data = json_encode(['data' => 'test']);
        $event = $this->createGetRequestEvent($type, $data);

        $this->listener->onKernelRequest($event);

        $this->assertEquals($event->getRequest()->request->get('data'), $isJson ? 'test' : null);
    }

    public function testBadJson()
    {
        $this->setExpectedException(BadRequestHttpException::class);
        $this->logger
            ->expects($this->once())
            ->method('debug');

        $event = $this->createGetRequestEvent('application/json', 'data=test');
        $this->listener->onKernelRequest($event);
    }

    /**
     * @param string $contentType
     * @param string $body
     * @return \PHPUnit_Framework_MockObject_MockObject|GetResponseEvent
     */
    private function createGetRequestEvent($contentType, $body)
    {
        $request = new Request([], [], [], [], [], [], $body);
        $request->headers->set('CONTENT_TYPE', $contentType);

        $event = $this->getMockBuilder(GetResponseEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $event->method('getRequest')->willReturn($request);
        return $event;
    }
}
