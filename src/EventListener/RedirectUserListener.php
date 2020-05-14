<?php

namespace App\EventListener;

use App\Entity\Chercheur;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;


class RedirectUserListener
{
    private $tokenStorage;
    private $router;

    public function __construct(TokenStorageInterface $t, RouterInterface $r)
    {
        $this->tokenStorage = $t;
        $this->router = $r;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!is_null($this->tokenStorage->getToken()) && $this->isUserLogged() && !$this->isUserActive()) {
            $currentRoute = $event->getRequest()->attributes->get('_route');
            if ($this->isForbiddenRoute($currentRoute)) {
                $response = new RedirectResponse($this->router->generate('inactive_account'));
                $event->setResponse($response);
            }
        }
    }

    private function isUserLogged()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        return $user instanceof Chercheur;
    }

    private function isUserActive()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        return $user->getActif();
    }

    private function isForbiddenRoute($currentRoute)
    {
        return !in_array(
            $currentRoute,
            ['contact', 'logout', 'inactive_account']
        );
    }
}
