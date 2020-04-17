<?php


namespace crystlbrd\ControllerExt\ControllerTraits;


use crystlbrd\Values\ArrVal;

/**
 * Trait Router
 * Handles relocating the client
 * @package crystlbrd\ControllerExt\ControllerTrait
 */
trait Router
{
    /// METHODS

    /**
     * Relocates the client and stops the script
     * @param string $url
     * @param int $status
     */
    protected function relocate(string $url, int $status = 302): void
    {
        header('Location: ' . $url, true, $status);
        exit;
    }

    /**
     * Relocates the client to a specific page
     * @param string $url the URL to relocate
     * @param array $get additional GET parameters
     * @param array $options additional options
     */
    protected function relocateTo(string $url, array $get = [], array $options = []): void
    {
        // OPTIONS

        $opt = ArrVal::merge([
            'anchor' => null,   // URL anchor (/some/url#anchor)
            'status' => 302     // HTTP Status for relocating (default: 302)
        ], $options);


        // LOGIC

        // base url
        $url = URL . $url;

        // add GET parameters
        $i = 0;
        foreach ($get as $k => $v) {
            $url .= ($i ? '&' : '?') . urlencode($k) . '=' . urlencode($v);
            $i++;
        }

        // add anchor if required
        if ($opt['anchor']) {
            $url .= '#' . $opt['anchor'];
        }

        // relocate
        $this->relocate($url, $opt['status']);
    }

    /**
     * Relocates to home/index
     * @param array $get
     * @param string|null $anchor
     */
    protected function relocateToHome(array $get = [], string $anchor = null): void
    {
        $this->relocateTo('', $get, ['anchor' => $anchor]);
    }
}