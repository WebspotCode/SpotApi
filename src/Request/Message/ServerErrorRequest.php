<?php declare(strict_types = 1);

namespace Spot\Api\Request\Message;

class ServerErrorRequest extends AbstractRequest
{
    /** {@inheritdoc} */
    public function getRequestName() : string
    {
        return 'error.serverError';
    }
}
