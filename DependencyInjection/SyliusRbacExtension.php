<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\RbacBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class SyliusRbacExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $config = $this->processConfiguration($this->getConfiguration($config, $container), $config);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load(sprintf('driver/%s.xml', $config['driver']));

        $this->registerResources('sylius', $config['driver'], $config['resources'], $container);

        $configFiles = [
            'services.xml',
            'templating.xml',
            'twig.xml',
        ];

        foreach ($configFiles as $configFile) {
            $loader->load($configFile);
        }

        $container->setAlias('sylius.authorization_identity_provider', $config['identity_provider']);
        $container->setAlias('sylius.permission_map', $config['permission_map']);
        $container->setAlias('sylius.authorization_checker', $config['authorization_checker']);

        $container->setParameter('sylius.rbac.security_roles', $config['security_roles']);

        $container->setParameter('sylius.rbac.default_roles', $config['roles']);
        $container->setParameter('sylius.rbac.default_roles_hierarchy', $config['roles_hierarchy']);

        $container->setParameter('sylius.rbac.generate_resource_permissions', $config['generate_resource_permissions']);
        $container->setParameter('sylius.rbac.generate_resource_permissions_group', $config['generate_resource_permissions_group']);
        $container->setParameter('sylius.rbac.default_permissions', $config['permissions']);
        $container->setParameter('sylius.rbac.default_permissions_hierarchy', $config['permissions_hierarchy']);
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $this->prependSyliusResource($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function prependSyliusResource(ContainerBuilder $container)
    {
        if (!$container->hasExtension('sylius_resource')) {
            return;
        }

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('resource_integration.xml');

        $container->prependExtensionConfig('sylius_resource', [
            'authorization_checker' => 'sylius.resource_controller.authorization_checker.rbac',
        ]);
    }
}
