<?php


namespace LazyJsBundle\Twig;

/**
 * Class LazyJsForTwig
 * @package LazyJsBundle\Twig
 */
class LazyJsForTwig
{
    /**
     * Names of TWIG Functions
     */
    const JS_MVC_PASS_HANDLER = 'jsMvcPassHandler';
    const JS_MVC_PASS_DATA = 'jsMvcPassData';
    const JS_MVC_REGISTER_ELEMENT = 'jsMvcRegisterElement';


    /**
     * @var string
     */
    private $controller;

    /**
     * @var string
     */
    private $method;

    /**
     * LazyJsForTwig constructor.
     * @param string $controller
     * @param string $method
     */
    public function __construct(string $controller, string $method)
    {
        $this->controller = $controller;
        $this->method = $method;
    }


    /**
     * Returns HTML describing which handle (controller and his method) should be called in JS
     * @return string
     */
    public function passHandler()
    {
        return '<input data-js-mvc-routing="js-mvc-route" type="hidden" style="display: none!important" value="' .
            $this->controller . '::' . $this->method .
            '"/>';
    }

    /**
     * Passes data of any serializable type to JS
     * @param string $name
     * @param mixed $value
     * @return string
     */
    public function passData(string $name, $value)
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
        return "<input data-js-mvc-data=\"$name\" data-js-mvc-is-scalar=\"$intIsScalar\" type=\"hidden\" style=\"display: none!important\" value=\"$encodedValue\"/>";
    }

    /**
     * Registers this element, so it is passed to JS handler
     * @param string $elementName
     * @return string
     */
    public function registerThisElement(string $elementName)
    {
        $elementName = htmlspecialchars($elementName);
        return " data-js-mvc-element=\"$elementName\" ";
    }
}