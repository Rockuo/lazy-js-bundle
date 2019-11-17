<?php


namespace LazyJsBundle\Twig;

use Twig\Environment;
use Twig\TwigFunction;

/**
 * Class ManualLazyJsRegistrar
 * @package LazyJsBundle\Twig
 */
class ManualLazyJsRegistrar
{

    /**
     * Registers lazy J́S functions to Twig Environment
     * @param Environment $twigEnvironment
     * @param string $controller
     * @param string $method
     */
    public static function register(Environment $twigEnvironment, string $controller, string $method)
    {
        $lazyJsForTwig = new LazyJsForTwig($controller, $method);
        $twigEnvironment->addFunction(
            new TwigFunction(
                LazyJsForTwig::JS_MVC_PASS_HANDLER,
                [$lazyJsForTwig, 'passHandler'],
                ['is_safe' => ['html']]
            )
        );
        $twigEnvironment->addFunction(
            new TwigFunction(
                LazyJsForTwig::JS_MVC_PASS_DATA,
                [$lazyJsForTwig, 'passData'],
                ['is_safe' => ['html']]
            )
        );
        $twigEnvironment->addFunction(
            new TwigFunction(
                LazyJsForTwig::JS_MVC_REGISTER_ELEMENT,
                [$lazyJsForTwig, 'registerThisElement'],
                ['is_safe' => ['html']]
            )
        );
    }
}