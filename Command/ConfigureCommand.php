<?php

/*
 * This file is part of the QuickInstallBundle package.
 *
 * (c) EXSyst
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EXSyst\Bundle\QuickInstallBundle\Command;

use EXSyst\Bundle\QuickInstallBundle\Configurator\ConfiguratorInterface;
use EXSyst\Bundle\QuickInstallBundle\Util\BundleResolver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Guilhem N. <egetick@gmail.com>
 */
class ConfigureCommand extends Command
{
    private $bundleResolver;
    private $configurator;

    /**
     * @internal
     */
    public function __construct(BundleResolver $bundleResolver, ConfiguratorInterface $configurator)
    {
        parent::__construct();

        $this->bundleResolver = $bundleResolver;
        $this->configurator = $configurator;
    }

    protected function configure()
    {
        $this
            ->setName('configure:bundle')
            ->setDescription('Configures a bundle')
            ->addArgument('bundle', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $bundle = $input->getArgument('bundle');
        $bundle = $this->bundleResolver->resolve($bundle);

        if ($this->configurator->supports($bundle)) {
            $this->configurator->configure($bundle, $io);
        } else {
            $io->error(sprintf('No configurator found for "%s"', $bundle->getClass()));
        }
    }
}
