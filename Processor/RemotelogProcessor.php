<?php

namespace Hatimeria\RemotelogBundle\Processor;


class RemotelogProcessor
{
    private $container;
    private $statusCode;
    
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function processRecord(array $record)
    {
        $record['url'] = $_SERVER['REQUEST_URI'];
        $record['client_ip'] = $_SERVER['REMOTE_ADDR'];
        if($this->statusCode) {
            $record['code'] = $this->statusCode;
        }
        $record['parameters']['post'] = $_POST;
        
        return $record;
    }
    
    public function onCoreResponse(\Symfony\Component\HttpKernel\Event\FilterResponseEvent $event)
    {
        $this->statusCode = $event->getResponse()->getStatusCode();
    }
}