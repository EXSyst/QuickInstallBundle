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

abstract class AbstractConfigurator
{
    abstract public function configure(Bundle $bundle, SymfonyStyle $io);
    abstract public function supports(Bundle $bundle): bool;

    /**
     * @return string|null
     */
    final protected function ask(SymfonyStyle $io, string $question, string $default = null)
    {
        $message = $this->getMessage($question, $default);

        return $io->ask($message, $default);
    }

    final protected function askConfirmation(SymfonyStyle $io, string $question, bool $default = true): bool
    {
        $message = $this->getMessage($question, $default ? 'yes' : 'no');

        return $io->confirm($message, $default);
    }

    private function getMessage(string $question, string $default = null): string
    {
        $message = sprintf('<info>%s</info> ', $question);
        if (null !== $default) {
            $message .= sprintf('[<comment>%s</comment>]', $default);
        }
        $message .= ': ';

        return $message;
    }
}
