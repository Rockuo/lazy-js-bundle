<?php
/**
 * Created by PhpStorm.
 * User: rockuo
 * Date: 10.04.19
 * Time: 20:29
 */

namespace LazyJsBundle\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Automatic twig extension, when using with symfony symfony
 * @package LazyJsBundle\Twig
 */
class LazyJsExtension extends AbstractExtension
{
    /**
     * @var RequestStack
     */
    protected $requestStack;


    /**
     * LazyJsExtension constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions()
    {
        [$controller, $method] = explode(
            '::',
            $this->requestStack->getCurrentRequest()->attributes->get('_controller')
        );
        $lazyJsForTwig = new LazyJsForTwig($controller, $method);
        return [
            new TwigFunction(
                LazyJsForTwig::JS_MVC_PASS_HANDLER,
                [$lazyJsForTwig, 'passHandler'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                LazyJsForTwig::JS_MVC_PASS_DATA,
                [$lazyJsForTwig, 'passData'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                LazyJsForTwig::JS_MVC_REGISTER_ELEMENT,
                [$lazyJsForTwig, 'registerThisElement'],
                ['is_safe' => ['html']]
            ),
        ];
    }
}