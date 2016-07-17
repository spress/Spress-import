<?php

use Spress\Import\ProviderCollection;
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
        $definition->addOption('post-layout', null, CommandDefinition::VALUE_REQUIRED, 'Layout for post items');
        $definition->addOption('fetch-images', null, null, 'Fetch images used by the Wordpress blog');
        $definition->addOption('not-replace-urls', null, null, 'Do not replace old Wordpress URLs to the new Spress path');
        $definition->addOption('assets-dir', null, CommandDefinition::VALUE_REQUIRED, 'Relative directory to content folder for storing the fetched images', 'assets');

        return $definition;
    }

    /**
     * Executes the current command.
     *
     * @param \Yosymfony\Spress\Core\IO\IOInterface $io        Input/output interface.
     * @param array                                 $arguments Arguments passed to the command.
     * @param array                                 $options   Options passed to the command.
     *
     * @return null|int null or 0 if everything went fine, or an error code.
     */
    public function executeCommand(IOInterface $io, array $arguments, array $options)
    {
        $style = new SpressImportConsoleStyle($io);
        $file = $arguments['file'];
        $srcPath = __DIR__.'/../../../../';

        $style->title('Importing from Wordpress WXR file');

        $providerCollection = new ProviderCollection([
            'wxr' => new WxrProvider(),
        ]);
        $assetsDir = $options['assets-dir'];
        $providerManager = new ProviderManager($providerCollection, $srcPath, $assetsDir);

        if ($options['dry-run'] == true) {
            $providerManager->enableDryRun();
        }

        if ($options['fetch-images'] == true) {
            $providerManager->enableFetchResources();
        }

        if ($options['not-replace-urls'] == true) {
            $providerManager->doNotReplaceUrls();
        }

        if (is_null($options['post-layout']) == false) {
            $providerManager->setPostLayout($options['post-layout']);
        }

        try {
            $itemResults = $providerManager->import('wxr', [
                'file' => $file,
            ]);

            $style->ResultItems($itemResults);
        } catch (Exception $e) {
            $style->error($e->getMessage());
        }
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
            'description' => 'A plugin for importing from Wordpress blogs to Spress',
            'author' => 'Victor Puertas',
            'license' => 'MIT',
        ];
    }
}
