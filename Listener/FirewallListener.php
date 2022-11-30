<?php
/*
 * This file is part of the Yipikai Firewall Bundle package.
 *
 * (c) Austral <support@yipikai.studio>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yipikai\FirewallBundle\Listener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Yipikai\FirewallBundle\Configuration\FirewallConfiguration;
use Yipikai\FirewallBundle\Services\Firewall;

/**
 * Yipikai Firewall Listener.
 * @author Matthieu Beurel <matthieu@yipikai.studio>
 */
class FirewallListener
{

  /**
   * @var Firewall
   */
  protected Firewall $firewall;

  /**
   * @var FirewallConfiguration
   */
  protected FirewallConfiguration $firewallConfiguration;

  /**
   * @param Firewall $firewall
   * @param FirewallConfiguration $firewallConfiguration
   */
  public function __construct(Firewall $firewall, FirewallConfiguration $firewallConfiguration)
  {
    $this->firewall = $firewall;
    $this->firewallConfiguration = $firewallConfiguration;
  }

  /**
   * @param RequestEvent $event
   *
   * @return void
   * @throws TransportExceptionInterface
   */
  public function execute(RequestEvent $event)
  {
    $checkAccess = false;
    $redirect = null;
    if($this->firewallConfiguration->get("filters.environnement.dev.enabled") === true && $event->getRequest()->server->get("APP_ENV") === "dev")
    {
      $checkAccess = true;
      $redirect = $this->firewallConfiguration->get("filters.environnement.dev.redirect");
    }
    elseif($this->firewallConfiguration->get("filters.environnement.prod.enabled") === true && $event->getRequest()->server->get("APP_ENV") === "prod")
    {
      $checkAccess = true;
      $redirect = $this->firewallConfiguration->get("filters.environnement.prod.redirect");
    }
    elseif($this->firewallConfiguration->get("filters.path.enabled") === true && ($paths = $this->firewallConfiguration->get("filters.path.list")))
    {
      foreach($paths as $path)
      {
        if(strpos($event->getRequest()->getRequestUri(), $path) === 0)
        {
          $checkAccess = true;
          $redirect = $this->firewallConfiguration->get("filters.path.redirect");
        }
      }
    }
    if($checkAccess) {
      if(!$this->firewall->authorize($event->getRequest()))
      {
        $response = new RedirectResponse($redirect, 301);
        $event->setResponse($response);
      }
    }
  }


}