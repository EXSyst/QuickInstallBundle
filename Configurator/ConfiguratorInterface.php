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

interface ConfiguratorInterface
{
    public function configure(Bundle $bundle, SymfonyStyle $io);
    public function supports(Bundle $bundle): bool;
}
