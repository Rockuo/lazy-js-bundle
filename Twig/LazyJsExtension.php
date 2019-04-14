<?php
/**
 * Created by PhpStorm.
 * User: rockuo
 * Date: 10.04.19
 * Time: 20:29
 */

// src/Twig/AppExtension.php
namespace App\LazyJs\ExtensionBundle\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LazyJsExtension extends AbstractExtension
{

    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('jsMvcAction', [$this, 'getAction'], ['is_safe' => ['html']]),
            new TwigFunction('jsMvcData', [$this, 'getData'], ['is_safe' => ['html']]),
            new TwigFunction('jsMvcElement', [$this, 'getElement'], ['is_safe' => ['html']]),
        ];
    }

    public function getAction()
    {
        if (!$this->requestStack->getCurrentRequest()) {
            return '';
        }

        return '<input data-js-mvc-routing="js-mvc-route" type="hidden" style="display: none!important" value="' .
            $this->requestStack->getCurrentRequest()->attributes->get('_controller') .
            '"/>';
    }

    public function getData(string $key, $value)
    {
        $isScalar = is_scalar($value);
        if (!$isScalar) {
            $encodedValue = \json_encode($value);
        } else {
            $encodedValue = \json_encode([$value]); // preserve data type
        }
        $encodedValue = str_replace('&', '&amp;', $encodedValue);
        $encodedValue = str_replace('"', '&quot;', $encodedValue);
        $intIsScalar = (int)$isScalar;
        return "<input data-js-mvc-data=\"$key\" data-js-mvc-is-scalar=\"$intIsScalar\" type=\"hidden\" style=\"display: none!important\" value=\"$encodedValue\"/>";
    }

    public function getElement(string $key)
    {
        $key = htmlspecialchars($key);
        return " data-js-mvc-element=\"$key\" ";
    }
}