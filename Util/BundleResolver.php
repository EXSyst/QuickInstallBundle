<?php

/*
 * This file is part of the QuickInstallBundle package.
 *
 * (c) EXSyst
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EXSyst\Bundle\QuickInstallBundle\Util;

use EXSyst\Bundle\QuickInstallBundle\Bundle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Bundle\Bundle as BaseBundle;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @internal
 */
class BundleResolver
{
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function resolve(string $bundle): Bundle
    {
        foreach ($this->kernel->getBundles() as $b) {
            if ($b->getName() === $bundle || get_class($b) === $bundle) {
                return new Bundle(get_class($b));
            } elseif ($b->getContainerExtension() && $b->getContainerExtension()->getAlias() === $bundle) {
                return new Bundle(get_class($b));
            }
        }

        if (class_exists($bundle)) {
            return new Bundle($bundle);
        }

        $classFinder = new ClassFinder();
        $finder = new Finder();
        $finder
            ->in(dirname($this->kernel->getRootDir()))
            ->name('*Bundle.php');
        $bundleClass = $classFinder->findClassByShortName($bundle, $finder, BaseBundle::class);
        if (null !== $bundleClass) {
            return new Bundle($bundleClass);
        }

        throw new \LogicException(sprintf('Unable to find the bundle "%s"'), $bundle);
    }
}
