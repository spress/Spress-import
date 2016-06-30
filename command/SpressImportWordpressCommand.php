<?php

use Spress\Import\ProviderManager;
use Spress\Import\Provider\WxrProvider;
use Yosymfony\Spress\Core\IO\IOInterface;
use Yosymfony\Spress\Plugin\CommandDefinition;
use Yosymfony\Spress\Plugin\CommandPlugin;

class SpressImportWordpressCommand extends CommandPlugin
{
    /**
     * Gets the command's definition.
     *
     * @return \Yosymfony\Spress\Plugin\CommandDefinition Definition of the command.
     */
    public function getCommandDefinition()
    {
        $definition = new CommandDefinition('import:wordpress');
        $definition->setDescription('Import a blog from Wordpress');
        $definition->setHelp('Import command for WXR files generated by Wordpress');

        $definition->addArgument('file', CommandDefinition::REQUIRED, 'Path to WXR file');
        $definition->addOption('dry-run', null, null);

        return $definition;
    }

    /**
     * Executes the current command.
     *
     * @param \Yosymfony\Spress\Core\IO\IOInterface $io Input/output interface.
     * @param array $arguments Arguments passed to the command.
     * @param array $options Options passed to the command.
     *
     * @return null|int null or 0 if everything went fine, or an error code.
     */
    public function executeCommand(IOInterface $io, array $arguments, array $options)
    {
        $file = $arguments['file'];
        $srcDir = __DIR__.'/../../../';
        $providerManager = new ProviderManager([
            'wxr' => new WxrProvider(),
        ], $srcPath);

        if (isset($options['dry-run'])) {
            $providerManager->enableDryRun();
        }

        $itemResults = $providerManager->import('wxr', [
            'file' => $file,
        ]);
    }

    /**
     * Gets the metas of a plugin.
     *
     * Standard metas:
     *   - name: (string) The name of plugin.
     *   - description: (string) A short description of the plugin.
     *   - author: (string) The author of the plugin.
     *   - license: (string) The license of the plugin.
     *
     * @return array
     */
    public function getMetas()
    {
        return [
            'name' => 'spress/spress-import-wordpress',
            'description' => 'A plugin for importing from various blog platform to Spress',
            'author' => 'Victor Puertas',
            'license' => 'MIT',
        ];
    }
}
