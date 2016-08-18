<?php

/*
 * This file is part of the QuickInstallBundle package.
 *
 * (c) EXSyst
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EXSyst\Bundle\QuickInstallBundle\Configurator\Bundle;

use Dunglas\ActionBundle\DunglasActionBundle;
use Dunglas\ActionBundle\DependencyInjection\DunglasActionExtension;
use EXSyst\Bundle\QuickInstallBundle\Bundle;
use EXSyst\Bundle\QuickInstallBundle\Configurator\AbstractBundleConfigurator;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Extension\ConfigurationExtensionInterface;

/**
 * @internal
 */
final class DunglasActionConfigurator extends AbstractBundleConfigurator
{
    protected function doConfigure(Bundle $bundle, SymfonyStyle $io)
    {
        if (!$io->confirm('Configure automatically registered directories?', false)) {
            return;
        }

        $appConfig = $this->getAppConfig($bundle);
        $defaultDirectories = $appConfig['directories'];

        $directories = [];
        while (true) {
            $default = 'stop';
            if (count($defaultDirectories)) {
                $default = array_shift($defaultDirectories);
            }

            $directory = $io->ask('Register a new directory?', $default);
            if (null !== $directory && 'stop' !== $directory) {
                $directories[] = $directory;
            } else {
                if ('stop' === $default) {
                    break;
                }
            }
        }

        $config = $this->getConfig($bundle, $io);
        // If not already the current config
        if ($directories !== $appConfig['directories']) {
            $config['directories'] = $directories;
        }

        $this->saveConfig($config, $bundle, $io);
    }

    public function supports(Bundle $bundle): bool
    {
        return $bundle->getClass() === DunglasActionBundle::class;
    }

    protected function getExtension(Bundle $bundle): ConfigurationExtensionInterface
    {
        return new DunglasActionExtension();
    }
}
