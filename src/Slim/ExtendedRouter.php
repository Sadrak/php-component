<?php
/**
 * This file is part of the PHP components.
 *
 * For the full copyright and license information, please view the LICENSE.md file distributed with this source code.
 *
 * @license MIT License
 * @link    https://github.com/ansas/php-component
 */

namespace Ansas\Slim;

use Ansas\Component\Locale\Localization;
use FastRoute\RouteParser;
use InvalidArgumentException;
use Slim\Router;

/**
 * Class ExtendedRouter
 *
 * @package Ansas\Slim\Handler
 * @author  Ansas Meyer <mail@ansas-meyer.de>
 */
class ExtendedRouter extends Router
{
    /**
     * @var string Language identifier for localized path (routes)
     */
    protected $languageIdentifier;

    /**
     * @var Localization Localization data
     */
    protected $localization;

    /**
     * @var bool Flag if default language is to be removed from path
     */
    protected $omitDefaultLanguage;

    /**
     * Create new instance.
     *
     * @param RouteParser $parser
     *
     * @return static
     */
    public static function create(RouteParser $parser = null)
    {
        return new static($parser);
    }

    /**
     * Clear all defined routes.
     */
    public function clearRoutes()
    {
        $this->routes       = [];
        $this->routeCounter = 0;
    }

    /**
     * Get the language identifier.
     *
     * @return string|null
     */
    public function getLanguageIdentifier()
    {
        return $this->languageIdentifier;
    }

    /**
     * @return Localization|null
     */
    public function getLocalization()
    {
        return $this->localization;
    }

    /**
     * Flag if default language is to be removed from path.
     *
     * @return bool
     */
    public function isOmitDefaultLanguage()
    {
        return (bool) $this->omitDefaultLanguage;
    }

    /**
     * {@inheritDoc}
     */
    public function pathFor($name, array $data = [], array $queryParams = [], $lang = null)
    {
        $url = $this->relativePathFor($name, $data, $queryParams, $lang);

        if ($this->basePath) {
            $url = $this->basePath . $url;
        }

        // Remove trailing ? (is set if $queryParam list was not empty but only contained empty values)
        $url = rtrim($url, '?');

        return $url;
    }

    /**
     * {@inheritDoc}
     */
    public function relativePathFor($name, array $data = [], array $queryParams = [], $lang = null)
    {
        // Get values needed for localized paths
        $identifier = $this->getLanguageIdentifier();
        $locales    = $this->getLocalization();

        // Return default behavior if no localized path is applicable
        if (!$identifier || !$locales) {
            return parent::relativePathFor($name, $data, $queryParams);
        }

        // determine locale to use for path
        $locale = $locales->getActive();
        $lang   = $lang ?: (isset($data[$identifier]) ? $data[$identifier] : null);
        if ($lang) {
            $locale = $locales->find($lang);
            if (!$locale) {
                throw new InvalidArgumentException(sprintf("Invalid data '%s' for URL argument '%s'",
                    $lang,
                    $identifier));
            }
        }

        // Set language identifier for path
        // Note: Add slash as prefix as this is normally part of the identifier (in optional language mode)
        $data[$identifier] = '/' . $locale->getLanguage();

        $path = parent::relativePathFor($name, $data, $queryParams);

        // Cut out localization part (identifier) for default language if wanted
        if ($this->isOmitDefaultLanguage() && $locale == $locales->getDefault()) {
            $path = preg_replace('/\/' . $locale->getLanguage() . '(\/|$)/', '/', $path);
        }

        // Sanitize url (remove prior set slash) if duplicate
        $path = str_replace('//', '/', $path);

        return $path;
    }

    /**
     * {@inheritDoc}
     *
     * This method override just fixes the wrong return type "self" instead of "$this"
     *
     * @return $this
     */
    public function setCacheFile($cacheFile)
    {
        parent::setCacheFile($cacheFile);

        return $this;
    }

    /**
     * Set the language identifier string for building path names.
     *
     * @param string|null $languageIdentifier [optional]
     *
     * @return $this
     */
    public function setLanguageIdentifier($languageIdentifier = null)
    {
        $this->languageIdentifier = $languageIdentifier;

        return $this;
    }

    /**
     * Set Localization.
     *
     * @param Localization|null $localization [optional]
     *
     * @return $this
     */
    public function setLocalization(Localization $localization = null)
    {
        $this->localization = $localization;

        return $this;
    }

    /**
     * Set flag if default language is to be removed from path.
     *
     * @param bool $omitDefaultLanguage [optional]
     *
     * @return $this
     */
    public function setOmitDefaultLanguage($omitDefaultLanguage = true)
    {
        $this->omitDefaultLanguage = $omitDefaultLanguage;

        return $this;
    }
}
