<?php
/*
 * This file is part of the Yipikai Firewall Bundle package.
 *
 * (c) Austral <support@yipikai.studio>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yipikai\FirewallBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Yipikai Firewall Bundle.
 * @author Matthieu Beurel <matthieu@yipikai.studio>
 */
class YipikaiFirewallBundle extends Bundle
{

  /**
   * @param ContainerBuilder $container
   */
  public function build(ContainerBuilder $container)
  {
    parent::build($container);
  }
  
  
}
