<?php
/*
 * This file is part of the Yipikai Firewall Bundle package.
 *
 * (c) Yipikai <support@yipikai.studio>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yipikai\FirewallBundle\Event;

/**
 * Austral Firewall Event.
 * @author Matthieu Beurel <matthieu@yipikai.studio>
 * @final
 */
class FirewallEvent
{

  const EVENT_YIPIKAI_FIREWALL_ENABLED = "yipikai.event.firewall.enabled";
  const EVENT_YIPIKAI_FIREWALL_AUTHORIZE = "yipikai.event.firewall.authorize";

  /**
   * @var string|null
   */
  protected ?string $type = null;

  /**
   * @var bool
   */
  protected bool $isEnabled = false;

  /**
   * @var bool
   */
  protected bool $isAuthorize = false;

  /**
   * @var string|null
   */
  protected ?string $redirect = null;

  public function __construct()
  {

  }

  /**
   * @return string|null
   */
  public function getType(): ?string
  {
    return $this->type;
  }

  /**
   * @param string|null $type
   *
   * @return FirewallEvent
   */
  public function setType(?string $type): FirewallEvent
  {
    $this->type = $type;
    return $this;
  }

  /**
   * @return bool
   */
  public function getIsEnabled(): bool
  {
    return $this->isEnabled;
  }

  /**
   * @param bool $isEnabled
   *
   * @return FirewallEvent
   */
  public function setIsEnabled(bool $isEnabled): FirewallEvent
  {
    $this->isEnabled = $isEnabled;
    return $this;
  }

  /**
   * @return bool
   */
  public function getIsAuthorize(): bool
  {
    return $this->isAuthorize;
  }

  /**
   * @param bool $isAuthorize
   *
   * @return FirewallEvent
   */
  public function setIsAuthorize(bool $isAuthorize): FirewallEvent
  {
    $this->isAuthorize = $isAuthorize;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getRedirect(): ?string
  {
    return $this->redirect;
  }

  /**
   * @param string|null $redirect
   *
   * @return FirewallEvent
   */
  public function setRedirect(?string $redirect): FirewallEvent
  {
    $this->redirect = $redirect;
    return $this;
  }

}