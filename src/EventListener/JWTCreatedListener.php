<?php

namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener
{

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        //Get actual user
        /** @var User $user */
        $user = $event->getUser();
        $request = $this->requestStack->getCurrentRequest();
        $payload       = $event->getData();
        $payload['nom'] = $user->getNom();
        $payload['prenom'] = $user->getPrenom();
        $payload['solde'] = $user->getSolde();
        unset($payload['roles']);

        $event->setData($payload);

        $header        = $event->getHeader();
        $header['cty'] = 'JWT';

        $event->setHeader($header);
    }

}