<?php


namespace crystlbrd\ControllerExt\ControllerTraits;

use crystlbrd\Values\ArrVal;
use Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Trait Twig
 * Handles rendering of Twig Templates
 * @package crystlbrd\ControllerExt\ControllerTraits
 */
trait Twig
{
    /// SETTINGS

    /**
     * @var array Additional Twig Environment Options
     */
    protected $_SETTING_Twig_EnvironmentOptions = [
        'auto_reload' => true
    ];

    /**
     * @var string Path of template folder
     */
    protected $_SETTING_Twig_PathToTemplates = APP . 'view/';

    /**
     * @var string Path of cache folder
     */
    protected $_SETTING_Twig_PathToCache = APP . 'view/_cache/';


    /// PROPERTIES

    /**
     * @var Environment Twig
     */
    protected static $_Twig;

    /**
     * @var array Template Variables
     */
    protected static $_Hooks = [];


    /// METHODS

    /**
     * Defines default Twig variables
     */
    protected function defineDefaultHooks(): void
    {
        /**
         * This method is a placeholder.
         * Here you can define variables,
         * which are define per default
         * in your twig files.
         */

        self::$_Hooks = [
            'mini' => [
                'url' => URL
            ]
        ];
    }

    /*
     * Gets all template variables
     */
    protected function getTemplateData(array $data = []): array
    {
        return ArrVal::merge(self::$_Hooks, ['data' => $data]);
    }

    /**
     * Gets the correct file name
     * @param string $filename
     * @return bool|string false, if file could not be found
     */
    protected function getTemplateFile(string $filename)
    {
        if (file_exists($this->_SETTING_Twig_PathToTemplates . $filename)) return $filename;
        if (file_exists($this->_SETTING_Twig_PathToTemplates . $filename . '.html.twig')) return $filename . '.html.twig';

        return false;
    }

    /**
     * Renders a Twig template file
     * @param string $filename Filename
     * @param array $data Additional data
     * @param bool $echo Echo HTML?
     * @return string|null
     * @throws Exception
     */
    protected function render(string $filename, array $data = [], bool $echo = true): ?string
    {
        try {
            // look for the file
            if ($this->getTemplateFile($filename)) {
                // get the actuall file name
                $file = $this->getTemplateFile($filename);

                // init Twig
                $this->requireTwig();

                // render file
                $html = $this->_Twig->render($file, $this->getTemplateData($data));

                if (!$echo) return $html;
                echo $html;
            }
        } catch (Exception $e) {
            throw new Exception('Failed to render file!', 0, $e);
        }
    }

    /**
     * Initializes Twig
     */
    protected function requireTwig(): void
    {
        // init Twig, if not already done
        if (self::$_Twig !== null) {
            self::$_Twig = new Environment(
                new FilesystemLoader($this->_SETTING_Twig_PathToTemplates),
                ArrVal::merge(
                    [
                        'cache' => $this->_SETTING_Twig_PathToCache
                    ],
                    $this->_SETTING_Twig_EnvironmentOptions
                )
            );

            $this->defineDefaultHooks();
        }
    }
}