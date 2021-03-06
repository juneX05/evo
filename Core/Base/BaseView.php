<?php

namespace Evo\Base;

use Evo\Flash\Flash;
use Evo\Auth\Authorized;
use Exception;
use Throwable;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class BaseView
{

    /**
     * Render a view file
     * @throws Exception
     */
    public static function render(string $view, array $optional_view_data = [])
    {
        extract($optional_view_data, EXTR_SKIP);

        $file = dirname(__DIR__) . "/App/Views/$view";  // relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            throw new Exception("$file not found");
        }
    }

    /**
     * Render a view template using Twig
     * @throws Exception
     * @throws Throwable
     */
    public static function renderTemplate(string $template, array $optional_view_data = [])
    {
        echo static::getTemplate($template, $optional_view_data);
    }

    /**
     * Get the contents of a view template using Twig
     * @throws Exception|Throwable
     */
    public static function getTemplate(string $template, array $optional_view_data = []): string
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new FilesystemLoader(ROOT_PATH . '/App/Views');
            $twig = new Environment($loader);
            $twig->addGlobal('current_user', Authorized::getUser());
            $twig->addGlobal('flash_messages', Flash::getAllFlashNotifications());
        }

        return $twig->render($template, $optional_view_data);
    }
}
