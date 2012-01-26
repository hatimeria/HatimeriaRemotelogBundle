<?php
namespace Hatimeria\RemotelogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor,
    Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\Config\FileLocator;

class HatimeriaRemotelogExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor     = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        foreach (array('services') as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $builder = $container->getDefinition('remotelog');
        $container->setParameter("hatimeria_remotelog.cli", $config['cli']);

        $level = is_int($config['level']) ? $config['level'] : constant('Monolog\Logger::'.strtoupper($config['level']));

        $builder->replaceArgument(0, $config['host']);
        $builder->replaceArgument(1, $config['place']);
        $builder->replaceArgument(2, $config['route']);
        $builder->replaceArgument(3, $config['enabled']);
        $builder->replaceArgument(4, $level);
    }

}