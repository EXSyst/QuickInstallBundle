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

use Symfony\Component\Finder\Finder;

/**
 * @internal
 */
class ClassFinder
{
    /**
     * @return string|null
     */
    public function findClassByShortName(string $shortName, Finder $finder, $parent = null)
    {
        foreach ($this->findClassesByShortName($shortName,, $finder, $parent) as $class) {
            return $class;
        }
    }

    /**
     * @return array|\Traversable
     */
    public function findClassesByShortName(string $shortName, Finder $finder, $parent = null)
    {
        foreach ($this->findClasses($finder, $parent) as $class) {
            $reflectionClass = new \ReflectionClass($class);
            if ($shortName === $reflectionClass->getShortName()) {
                yield $class;
            }
        }

        return [];
    }

    /**
     * @return string|null
     */
    public function findClass(Finder $finder, string $parent = null)
    {
        foreach ($this->findClasses($finder, $parent) as $class) {
            return $class;
        }
    }

    /**
     * @return array|\Traversable
     */
    public function findClasses(Finder $finder, string $parent = null)
    {
        $finder->files();
        foreach ($finder as $file) {
            $sourceFile = $file->getRealpath();
            if (!preg_match('(^phar:)i', $sourceFile)) {
                $sourceFile = realpath($sourceFile);
            }

            foreach ($this->getClassesIn($sourceFile) as $class) {
                if (null === $parent || is_subclass_of($class, $parent)) {
                    yield $class;
                }
            }
        }

        return [];
    }

    /**
     * @param string|string[] $files
     *
     * @return array|\Traversable
     */
    public function getClassesIn($files)
    {
        $files = array_flip((array) $files);
        foreach ($files as $file => $v) {
            require_once $file;
        }

        $classes = [];
        $declared = get_declared_classes();
        foreach ($declared as $className) {
            $reflectionClass = new \ReflectionClass($className);
            $sourceFile = $reflectionClass->getFileName();
            if ($reflectionClass->isAbstract()) {
                continue;
            }
            if (isset($files[$sourceFile])) {
                if (!isset($classes[$className])) {
                    yield $className;
                }
                $classes[$className] = true;
            }
        }

        return [];
    }
}
