<?php

namespace spec\Spot\Api\Request\Executor;

use PhpSpec\ObjectBehavior;
use Pimple\Container;
use Prophecy\Argument;
use Spot\Api\Request\Executor\ExecutorInterface;
use Spot\Api\Request\RequestInterface;
use Spot\Api\Response\Message\Response;
use Spot\Api\Response\ResponseInterface;
use Spot\Api\Response\ResponseException;

/** @mixin  \Spot\Api\Request\Executor\ExecutorBus */
class ExecutorBusSpec extends ObjectBehavior
{
    /** @var  Container */
    private $container;

    /** @var  \Psr\Log\LoggerInterface */
    private $logger;

    /**
     * @param   \Psr\Log\LoggerInterface $logger
     */
    public function let($logger)
    {
        $this->container = new Container();
        $this->logger = $logger;
        $this->beConstructedWith($this->container, $logger);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(\Spot\Api\Request\Executor\ExecutorBus::class);
    }

    /**
     * @param  \Spot\Api\Request\RequestInterface $request
     */
    public function it_can_execute_successfully($request)
    {
        $request->getAcceptContentType()->willReturn('application/vnd.api+json');
        $requestName = 'request.name';
        $response = new Response($requestName, [], $request->getWrappedObject());
        $executorName = 'executor.test';
        $executor = new class($response) implements ExecutorInterface {
            private $response;
            public function __construct($response)
            {
                $this->response = $response;
            }
            public function executeRequest(RequestInterface $request) : ResponseInterface
            {
                return $this->response;
            }
        };
        $this->container[$executorName] = $executor;
        $this->setExecutor($requestName, $executorName)
            ->shouldReturn($this);

        $request->getRequestName()
            ->willReturn($requestName);

        $this->executeRequest($request)
            ->shouldReturn($response);
    }

    /**
     * @param  \Spot\Api\Request\RequestInterface $request
     */
    public function it_will_error_on_unsupported_request($request)
    {
        $requestName = 'request.name';
        $request->getRequestName()
            ->willReturn($requestName);
        $request->getAcceptContentType()->willReturn('application/vnd.api+json');

        $this->shouldThrow(ResponseException::class)->duringExecuteRequest($request);
    }

    /**
     * @param  \Spot\Api\Request\RequestInterface $request
     */
    public function it_will_error_on_undefined_Executor($request)
    {
        $requestName = 'request.name';
        $executorName = 'executor.test';

        $this->setExecutor($requestName, $executorName)
            ->shouldReturn($this);

        $request->getRequestName()
            ->willReturn($requestName);
        $request->getAcceptContentType()->willReturn('application/vnd.api+json');

        $this->shouldThrow(ResponseException::class)->duringExecuteRequest($request);
    }

    /**
     * @param  \Spot\Api\Request\RequestInterface $request
     */
    public function it_will_error_on_invalid_Executor($request)
    {
        $requestName = 'request.name';
        $executorName = 'executor.test';

        $this->setExecutor($requestName, $executorName)
            ->shouldReturn($this);
        $this->container[$executorName] = new \stdClass();

        $request->getRequestName()
            ->willReturn($requestName);
        $request->getAcceptContentType()->willReturn('application/vnd.api+json');

        $this->shouldThrow(\RuntimeException::class)->duringExecuteRequest($request);
    }
}
