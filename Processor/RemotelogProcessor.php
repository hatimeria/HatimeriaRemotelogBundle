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
        
        if(!empty($_POST)) {
            $record['parameters']['post'] = $_POST;
        }
        
        if(!empty($_FILES)) {
            $record['parameters']['files'] = $_FILES;
        }
        
        if(!empty($_SESSION)) {
            $record['parameters']['session'] = $_SESSION;
        }
        
        if(!empty($_COOKIE)) {
            $record['parameters']['cookie'] = $_COOKIE;
        }        

        if(isset($_SERVER['HTTP_USER_AGENT'])) {
            $record['parameters']['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        }
        
        if(isset($_SERVER['HTTP_REFERER'])) {
            $record['parameters']['referer'] = $_SERVER['HTTP_REFERER'];
        }
        
        return $record;
    }
    
    public function onCoreResponse(\Symfony\Component\HttpKernel\Event\FilterResponseEvent $event)
    {
        $this->statusCode = $event->getResponse()->getStatusCode();
    }
}