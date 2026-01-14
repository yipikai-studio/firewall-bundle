<?php
/*
 * This file is part of the Yipikai Firewall Bundle package.
 *
 * (c) Yipikai <support@yipikai.studio>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yipikai\FirewallBundle\Listener;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Yipikai\FirewallBundle\Configuration\FirewallConfiguration;
use Yipikai\FirewallBundle\Event\FirewallEvent;
use Yipikai\FirewallBundle\Services\Firewall;

/**
 * Yipikai Firewall Listener.
 * @author Matthieu Beurel <matthieu@yipikai.studio>
 */
class FirewallListener
{

  /**
   * @param Kernel $kernel
   * @param Firewall $firewall
   * @param FirewallConfiguration $firewallConfiguration
   * @param EventDispatcherInterface|null $dispatcher
   */
  public function __construct(
    #[Autowire(service: "kernel")] protected Kernel $kernel,
    #[Autowire(service: "yipikai.firewall")] protected Firewall $firewall,
    #[Autowire(service: "yipikai.firewall.config")] protected FirewallConfiguration $firewallConfiguration,
    #[Autowire(service: "event_dispatcher")] protected ?EventDispatcherInterface $dispatcher)
  {
  }

  /**
   * @param RequestEvent $event
   *
   * @return void
   * @throws TransportExceptionInterface
   */
  public function execute(RequestEvent $event): void
  {
    $checkAccess = false;
    $redirect = null;
    $type = null;
    if($this->firewallConfiguration->get("filters.environnement.dev.enabled") === true && $this->kernel->getEnvironment() === "dev")
    {
      $type = "env.dev";
      $checkAccess = true;
      $redirect = $this->firewallConfiguration->get("filters.environnement.dev.redirect");
    }
    elseif($this->firewallConfiguration->get("filters.environnement.prod.enabled") === true && $this->kernel->getEnvironment() === "prod")
    {
      $type = "env.prod";
      $checkAccess = true;
      $redirect = $this->firewallConfiguration->get("filters.environnement.prod.redirect");
    }
    elseif($this->firewallConfiguration->get("filters.path.enabled") === true && ($paths = $this->firewallConfiguration->get("filters.path.list")))
    {
      $type = "path";
      foreach($paths as $path)
      {
        if(str_starts_with($event->getRequest()->getRequestUri(), $path))
        {
          $checkAccess = true;
          $redirect = $this->firewallConfiguration->get("filters.path.redirect");
        }
      }
    }
    elseif($this->firewallConfiguration->get("filters.domain.enabled") === true && ($domains = $this->firewallConfiguration->get("filters.domain.list")))
    {
      $type = "domain";
      foreach($domains as $domain)
      {
        if(str_starts_with($event->getRequest()->getHost(), $domain))
        {
          $checkAccess = true;
          $redirect = $this->firewallConfiguration->get("filters.domain.redirect");
        }
      }
    }

    $firewallEvent = new FirewallEvent();
    $firewallEvent->setRedirect($redirect);
    $firewallEvent->setIsEnabled($checkAccess);
    $firewallEvent->setType($type);
    $this->dispatcher?->dispatch($firewallEvent, FirewallEvent::EVENT_YIPIKAI_FIREWALL_ENABLED);

    if($firewallEvent->getIsEnabled()) {
      $isAuthorize = $this->firewall->authorize($event->getRequest());
      $firewallEvent->setIsAuthorize($isAuthorize);
      $this->dispatcher?->dispatch($firewallEvent, FirewallEvent::EVENT_YIPIKAI_FIREWALL_AUTHORIZE);
      if(!$firewallEvent->getIsAuthorize())
      {
        $response = new RedirectResponse($firewallEvent->getRedirect(), 301);
        $event->setResponse($response);
      }
    }
  }


}