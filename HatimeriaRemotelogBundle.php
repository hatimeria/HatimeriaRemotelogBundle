<?php

namespace Hatimeria\RemotelogBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class HatimeriaRemotelogBundle extends Bundle
{
    /**
     * Boots the Bundle.
     */
    public function boot()
    {
        if('cli' === php_sapi_name()) {
            register_shutdown_function(array($this, 'handleCliShutdown'));
        }
    }    
    
    public function handleCliShutdown()
    {
        $c = $this->container;
        
        // @todo check if this issue is only in phpunit environment
        if(!is_object($c)) return;
        
        if(!$c->getParameter('hatimeria_remotelog.cli')) return;
            
        $error = error_get_last();
        if (is_null($error) || $error['type'] != 1 || ($error instanceof \ErrorException)) return;

        $message = sprintf('Fatal error: %s in %s on line %s', $error['message'], $error['file'], $error['line']);

        try {
            $log = $c->get('remotelog');
            $log->addLog(array(
                'message' => $message,
                'type'    => 'CRITICAL'
            ));
        } catch (\Exception $er) {
        }
    }        
}