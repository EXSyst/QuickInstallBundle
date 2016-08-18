<?php

/*
 * This file is part of the QuickInstallBundle package.
 *
 * (c) EXSyst
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EXSyst\Bundle\QuickInstallBundle\Configurator;

use EXSyst\Bundle\QuickInstallBundle\Bundle;
use EXSyst\Bundle\QuickInstallBundle\Util\KernelManipulator;
use Symfony\Component\Console\Style\SymfonyStyle;

final class GenericBundleConfigurator implements ConfiguratorInterface
{
    private $kernelManipulator;

    public function __construct(KernelManipulator $kernelManipulator)
    {
        $this->kernelManipulator = $kernelManipulator;
    }

    /**
     * {@inheritdoc}
     *
     * @return bool true if the bundle is registered in the kernel, false otherwise.
     */
    public function configure(Bundle $bundle, SymfonyStyle $io): bool
    {
        if ($this->kernelManipulator->hasBundle($bundle)) {
            $io->note(sprintf('The bundle "%s" is already registered in your kernel.', $bundle->getClass()));

            return true;
        }

        if (!$io->confirm(sprintf('Add the bundle "%s" to your kernel?', $bundle->getClass()))) {
            return false;
        }

        $registration = $this->kernelManipulator->addBundle($bundle);
        if ($registration) {
            $message = 'has been registered';
        } else {
            $message = 'registration failed';
        }
        $io->text(sprintf('<comment>%s</comment> %s in your kernel.', $bundle->getClass(), $message));

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Bundle $bundle): bool
    {
        return true;
    }
}
