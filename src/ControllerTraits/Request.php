<?php


namespace crystlbrd\ControllerExt\ControllerTraits;


trait Request
{
    /**
     * Checks, if a GET parameter is present and returns it, otherwise returns a default value
     * @param string $index Parameter name
     * @param null $default Default value
     * @return mixed|null
     */
    protected function get(string $index, $default = null)
    {
        return (isset($_GET) && !empty($_GET[$index]) ? $_GET[$index] : $default);
    }

    /**
     * Checks, if a POST parameter is present and returns it, otherwise returns a default value
     * @param string $index Parameter name
     * @param null $default Default value
     * @return mixed|null
     */
    protected function post(string $index, $default = null)
    {
        return (isset($_POST) && !empty($_POST[$index]) ? $_POST[$index] : $default);
    }
}