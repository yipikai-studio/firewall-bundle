<?php
/*
 * This file is part of the Yipikai Firewall Bundle package.
 *
 * (c) Yipikai <support@yipikai.studio>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yipikai\FirewallBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Yipikai Firewall Configuration.
 * @author Matthieu Beurel <matthieu@yipikai.studio>
 * @final
 */
class Configuration implements ConfigurationInterface
{

  /**
   * {@inheritdoc}
   */
  public function getConfigTreeBuilder(): TreeBuilder
  {
    $treeBuilder = new TreeBuilder('yipikai_firewall');
    $rootNode = $treeBuilder->getRootNode();
    $node = $rootNode->children();

    $node->scalarNode("uri")->end();

    $node->arrayNode("token")
      ->children()
        ->scalarNode("public")->isRequired()->end()
        ->scalarNode("private")->isRequired()->end()
        ->scalarNode("salt")->isRequired()->end()
      ->end()
    ->end();

    $node->arrayNode("persist_in_session")
      ->children()
        ->booleanNode("enabled")->defaultTrue()->end()
        ->integerNode("seconds")->isRequired()->end()
      ->end()
    ->end();

    $node->arrayNode("filters")
      ->children()
        ->arrayNode("environnement")
          ->children()
            ->arrayNode("dev")
              ->children()
                ->booleanNode("enabled")->defaultTrue()->end()
                ->scalarNode("redirect")->end()
              ->end()
            ->end()
            ->arrayNode("prod")
              ->children()
                ->booleanNode("enabled")->defaultFalse()->end()
                ->scalarNode("redirect")->end()
              ->end()
            ->end()
          ->end()
        ->end()
        ->arrayNode("path")
          ->children()
            ->booleanNode("enabled")->defaultFalse()->end()
            ->arrayNode("list")->scalarPrototype()->end()->end()
            ->scalarNode("redirect")->end()
          ->end()
        ->end()
      ->end()
    ->end();
    return $treeBuilder;
  }

  /**
   * @return array
   */
  public function getConfigDefault(): array
  {
    return array(
      "uri"                 =>  "",
      "token"               =>  array(
        "public"              =>  "",
        "private"             =>  "",
        "salt"                =>  "YOUR_SALT"
      ),
      "filters"             =>  array(
        "environnement"       =>  array(
          "dev"                 =>  array(
            "enabled"             =>  true,
            "redirect"            =>  ""
          ),
          "prod"                 =>  array(
            "enabled"             =>  false,
            "redirect"            =>  ""
          )
        ),
        "path"                 =>  array(
          "enabled"               =>  false,
          "list"                  =>  array(),
          "redirect"              =>  ""
        )
      )
    );
  }






}
