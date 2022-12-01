<?php
/*
 * This file is part of the Yipikai Firewall Bundle package.
 *
 * (c) Yipikai <support@yipikai.studio>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yipikai\FirewallBundle\Services;

use Symfony\Component\HttpClient\NativeHttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Yipikai\FirewallBundle\Configuration\FirewallConfiguration;

/**
 * Yipikai Firewall.
 * @author Matthieu Beurel <matthieu@yipikai.studio>
 */
class Firewall
{

  /**
   * @var FirewallConfiguration
   */
  protected FirewallConfiguration $firewallConfiguration;

  /**
   * @param FirewallConfiguration $firewallConfiguration
   *
   * @return void
   */
  public function __construct(FirewallConfiguration $firewallConfiguration)
  {
    $this->firewallConfiguration = $firewallConfiguration;
  }

  /**
   * @param Request $request
   *
   * @return boolean
   * @throws \Exception|TransportExceptionInterface
   */
  public function authorize(Request $request): bool
  {
    $dateNow = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
    if($this->firewallConfiguration->get("persist_in_session.enabled") === true &&
      ($yipikaiFirewallAuthoriseSession = $request->getSession()->get("yipikai-firewall-authorize"))
    )
    {
      if(($dateNow->getTimestamp() - $yipikaiFirewallAuthoriseSession["timestamp"]) < $this->firewallConfiguration->get('persist_in_session.seconds'))
      {
        $dateCheck = clone $dateNow;
        $dateCheck->setTimestamp($yipikaiFirewallAuthoriseSession["timestamp"]);
        $checksum = self::generateToken(
          $request->getHost(),
          $dateCheck,
          $this->firewallConfiguration->get('token.private'),
          $yipikaiFirewallAuthoriseSession["salt"]
        );

        if($checksum === $yipikaiFirewallAuthoriseSession["token"])
        {
          $salt = sha1(uniqid());
          $token = self::generateToken(
            $request->getHost(),
            $dateCheck,
            $this->firewallConfiguration->get('token.private'),
            $salt
          );
          $request->getSession()->set("yipikai-firewall-authorize", array(
            "token"     =>  $token,
            "timestamp" =>  $dateCheck->getTimestamp(),
            "salt"      =>  $salt
          ));
          return true;
        }
      }
    }

    $requestParameters["headers"] = array(
      "User-Agent"			      =>	"Yipikai-Firewall/1.0.0",
      "Accept" 					      =>	"*/*",
      "Content-Type"          =>  'application/json',
      "x-yipikai-timestamp"   =>  $dateNow->getTimestamp(),
      "x-yipikai-client-ip"   =>  $request->headers->get("x-real-ip", $request->getClientIp()),
      "x-yipikai-host"        =>  $request->getHost(),
      "x-yipikai-token"       =>  $this->firewallConfiguration->get('token.public'),
      "x-yipikai-hash"        =>  self::generateToken(
        $request->getHost(),
        $dateNow,
        $this->firewallConfiguration->get('token.private'),
        $this->firewallConfiguration->get('token.salt')
      )
    );
    try {
      $httpClient = new NativeHttpClient();
      $response = $httpClient->request("GET", $this->firewallConfiguration->get('uri'), $requestParameters);
    }  catch (TransportExceptionInterface|\Exception $e) {
    }
    $responseObject = json_decode($response->getContent(false));
    if($responseObject->status === "success")
    {
      $dateCheck = clone $dateNow;
      $dateCheck->setTimestamp($responseObject->timestamp);
      $checksum = self::generateToken(
        $request->getHost(),
        $dateCheck,
        $this->firewallConfiguration->get('token.private'),
        $this->firewallConfiguration->get('token.salt')
      );
      if($checksum === $responseObject->token)
      {
        $salt = sha1(uniqid());
        $token = self::generateToken(
          $request->getHost(),
          $dateCheck,
          $this->firewallConfiguration->get('token.private'),
          $salt
        );
        $request->getSession()->set("yipikai-firewall-authorize", array(
          "token"     =>  $token,
          "timestamp" =>  $dateCheck->getTimestamp(),
          "salt"      =>  $salt
        ));
        return true;
      }
    }
    return false;
  }

  /**
   * @param string $host
   * @param \DateTime $date
   * @param string $privateKey
   * @param string $salt
   *
   * @return string
   */
  public static function generateToken(string $host, \DateTime $date, string $privateKey, string $salt = "YOUR_SALT"): string
  {
    return hash("sha256", sprintf("%s_%s-%s_%s", $salt, $host, $date->format("Y-m-d_h:i:s"), $privateKey));
  }

}