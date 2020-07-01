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
    /// SETTINGS

    /**
     * @var int Defines how many URL should be cached per session
     */
    protected $_SETTING_Router_maxCachedLocations = 5;


    /// METHODS

    /**
     * Caches the current URL
     */
    protected function cacheCurrentLocation(): void
    {
        $this->cacheLocation($_GET['url']);
    }

    /**
     * Saves an URL to the cache
     * @param string $url
     */
    protected function cacheLocation(string $url): void
    {
        // Don't cache the same location twice
        if ($url != $this->getLastCachedLocation()) {
            // Get the current cache
            $cache = $this->getCache();

            // Delete oldest entries, if cache is full
            if (count($cache) >= $this->_SETTING_Router_maxCachedLocations) {
                unset($cache[0]);
            }

            // Save URL to cache
            $cache[] = $url;

            // Save cache to session
            $this->setCache(array_values($cache));
        }
    }

    /**
     * Returns the current cache
     * @return array
     */
    protected function getCache(): array
    {
        return (isset($_SESSION['mini']['router']['cache']) ? $_SESSION['mini']['router']['cache'] : []);
    }

    /**
     * Returns the last cached URL
     * @return string
     */
    protected function getLastCachedLocation(): string
    {
        $cache = array_reverse($this->getCache());
        return (isset($cache[0]) ? $cache[0] : '');
    }

    /**
     * Relocates to the last cached location
     * @param array $get additional GET parameter
     * @param array $options additional options
     */
    protected function goBack(array $get = [], array $options = []): void
    {
        $this->relocateTo($this->getLastCachedLocation() ?: '', $get, $options);
    }

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
            'base' => URL,      // Baselink
            'anchor' => null,   // URL anchor (/some/url#anchor)
            'status' => 302     // HTTP Status for relocating (default: 302)
        ], $options);


        // LOGIC

        // base url
        $url = $opt['base'] . $url;

        // add GET parameters
        $i = 0;
        foreach ($get as $k => $v) {
            $url .= ($i ? '&' : '?') . (!is_int($k) ? urlencode($k) . '=' : '') . urlencode($v);
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

    /**
     * Sets the cache
     * @param array $cache
     */
    protected function setCache(array $cache): void
    {
        $_SESSION['mini']['router']['cache'] = $cache;
    }
}