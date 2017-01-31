<?php
/**
 * This file is part of the PHP components.
 *
 * For the full copyright and license information, please view the LICENSE.md file distributed with this source code.
 *
 * @license MIT License
 * @link    https://github.com/ansas/php-component
 */

namespace Ansas\Component\File;

/**
 * Class CSVBase
 *
 * @package Ansas\Component\File
 * @author  Ansas Meyer <mail@preigu.com>
 */
class CsvBase
{
    /**
     * @var string CSV delimiter
     */
    protected $delimiter = ";";

    /**
     * @var string CSV enclosure
     */
    protected $enclosure = "\"";

    /**
     * @var string CSV escape
     */
    protected $escape = "\\";

    /**
     * @return string
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * @return string
     */
    public function getEnclosure()
    {
        return $this->enclosure;
    }

    /**
     * @return string
     */
    public function getEscape()
    {
        return $this->escape;
    }

    /**
     * Set CSV delimiter string.
     *
     * @param string $delimiter
     *
     * @return $this
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    /**
     * Set CSV enclosure string.
     *
     * @param string $enclosure
     *
     * @return $this
     */
    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;

        return $this;
    }

    /**
     * Set CSV escape string.
     *
     * @param string $escape
     *
     * @return $this
     */
    public function setEscape($escape)
    {
        $this->escape = $escape;

        return $this;
    }
}
