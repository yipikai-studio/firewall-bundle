<?php
/*
 * This file is part of the Yipikai Firewall Bundle package.
 *
 * (c) Yipikai <support@yipikai.studio>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Yipikai\FirewallBundle\Configuration;

use Austral\ToolsBundle\Configuration\BaseConfiguration;

/**
 * Yipikai Firewall Bundle.
 * @author Matthieu Beurel <matthieu@yipikai.studio>
 * @final
 */
Class FirewallConfiguration extends BaseConfiguration
{

  /**
   * @var string|null
   */
  protected ?string $prefix = "firewall";

  /**
   * @var int|null
   */
  protected ?int $niveauMax = null;

}