<?php

namespace App\EventListener\HttpKernel;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CorsSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onResponse'
        ];
    }

    public function onResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:5173');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-HTTP-Method-Override, Accept, Origin, X-User-Agent, X-Requested-With, X-HTTP-Method-Override, X-Auth-Token, X-Auth-Username, X-Auth-Password, X-Auth-Email, X-Auth-Phone, X-Auth-Name, X-Auth-Role, X-Auth-Id, X-Auth-Status, X-Auth-Enabled, X-Auth-Last-Login, X-Auth-Last-Logout, X-Auth-Last-Password-Change, X-Auth-Last-Password-Reset, X-Auth-Last-Password-Reset-Request, X-Auth-Last-Password-Reset-Request-At, X-Auth-Last-Password-Reset-Request-By, X-Auth-Last-Password-Reset-Request-By-Id, X-Auth-Last-Password-Reset-Request-By-Name, X-Auth-Last-Password-Reset-Request-By-Role, X-Auth-Last-Password-Reset-Request-By-Status, X-Auth-Last-Password-Reset-Request-By-Enabled, X-Auth-Last-Password-Reset-Request-By-Last-Login, X-Auth-Last-Password-Reset-Request-By-Last-Logout, X-Auth-Last-Password-Reset-Request-By-Last-Password-Change, X-Auth-Last-Password-Reset-Request-By-Last-Password-Reset, X-Auth-Last-Password-Reset-Request-By-Last-Password-Reset-Request, X-Auth-Last-Password-Reset-Request-By-Last-Password-Reset-Request-At, X-Auth-Last-Password-Reset-Request-By-Last-Password-Reset-Request-By, X-Auth-Last-Password-Reset-Request-By-Last-Password-Reset-Request-By-Id, X-Auth-Last-Password-Reset-Request-By-Last-Password-Reset-Request-By-Name, X-Auth-Last-Password-Reset-Request-By-Last-Password-Reset-Request-By-Role, X-Auth-Last-Password-Reset-Request-By-Last-Password-Reset-Request-By-Status');
    }

}