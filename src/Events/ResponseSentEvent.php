<?php namespace Carbontwelve\AzureDns\Events;

class ResponseSentEvent extends BaseResponseEvent
{
    public function getName()
    {
        return 'ResponseSentEvent';
    }
}
