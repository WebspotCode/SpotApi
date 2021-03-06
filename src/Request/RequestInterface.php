<?php declare(strict_types = 1);

namespace Spot\Api\Request;

interface RequestInterface extends \ArrayAccess
{
    public function getRequestName() : string;

    public function getAttributes() : array;

    public function getAcceptContentType() : string;
}
