<?php


namespace crystlbrd\ControllerExt;


use crystlbrd\ControllerExt\ControllerTraits\Database;
use crystlbrd\ControllerExt\ControllerTraits\Request;
use crystlbrd\ControllerExt\ControllerTraits\Router;
use crystlbrd\ControllerExt\ControllerTraits\Twig;

/**
 * Includes all Controller Traits
 * @package crystlbrd\ControllerExt
 */
class AllExtensions
{
    use Request;
    use Router;
    use Twig;
    use Database;
}