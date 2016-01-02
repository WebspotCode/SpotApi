<?php

namespace spec\Spot\Api\Response\Message;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\Spot\Api\Message\AttributesArrayAccessSpecTrait;
use Spot\Api\Response\Message\ServerErrorResponse;

require_once __DIR__ . '/../../Message/AttributesArrayAccessSpecTrait.php';

/** @mixin  \Spot\Api\Response\Message\ServerErrorResponse */
class ServerErrorResponseSpec extends ObjectBehavior
{
    use AttributesArrayAccessSpecTrait;

    private $name = 'error.serverError';
    private $contentType = 'application/vnd.api+json';
    private $request;

    /**
     * @param  \Spot\Api\Request\RequestInterface $request
     */
    public function let($request)
    {
        $this->request = $request;
        $request->getAcceptContentType()->willReturn($this->contentType);
        $this->beConstructedWith([], $request);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ServerErrorResponse::class);
    }

    public function it_can_give_its_name()
    {
        $this->getResponseName()
            ->shouldReturn($this->name);
    }

    public function it_can_get_its_content_type()
    {
        $this->getContentType()
            ->shouldReturn($this->contentType);
    }
}
