<?php
/*
 * This file is part of the Yipikai Firewall Bundle package.
 *
 * (c) Yipikai <support@yipikai.studio>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yipikai\FirewallBundle\EventSubscriber;

use Yipikai\FirewallBundle\Event\FirewallEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Yipikai Firewall EventSubscriber.
 * @author Matthieu Beurel <matthieu@yipikai.studio>
 */
class FirewallEventSubscriber implements EventSubscriberInterface
{

  /**
   * @return array[]
   */
  public static function getSubscribedEvents(): array
  {
    return [
      FirewallEvent::EVENT_YIPIKAI_FIREWALL_ENABLED     =>  ["enabled", 1024],
      FirewallEvent::EVENT_YIPIKAI_FIREWALL_AUTHORIZE   =>  ["authorize", 1024]
    ];
  }

  /**
   * @param FirewallEvent $firewallEvent
   *
   * @return void
   */
  public function enabled(FirewallEvent $firewallEvent)
  {

  }

  /**
   * @param FirewallEvent $firewallEvent
   *
   * @return void
   */
  public function authorize(FirewallEvent $firewallEvent)
  {

  }

}