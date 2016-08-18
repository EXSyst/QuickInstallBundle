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
use Symfony\Component\Console\Style\SymfonyStyle;

final class ChainConfigurator implements ConfiguratorInterface
{
    private $configurators;

    /**
     * @param AbstractConfigurator[] $configurators
     */
    public function __construct(array $configurators)
    {
        $this->configurators = $configurators;
    }

    /**
     * {@inheritdoc}
     */
    public function configure(Bundle $bundle, SymfonyStyle $io)
    {
        foreach ($this->configurators as $configurator) {
            if ($configurator->supports($bundle)) {
                $configurator->configure($bundle, $io);

                return;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Bundle $bundle): bool
    {
        foreach ($this->configurators as $configurator) {
            if ($configurator->supports($bundle)) {
                return true;
            }
        }

        return false;
    }
}
