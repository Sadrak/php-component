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
 * Class CsvBuilder
 *
 * @package Ansas\Component\File
 * @author  Ansas Meyer <mail@ansas-meyer.de>
 */
class CsvBuilder extends CsvBuilderBase
{
    /**
     * @var array[] CSV data
     */
    protected $data;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->header = [];
        $this->data   = [];
    }

    /**
     * Convert object into CSV string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getCsv();
    }

    /**
     * Create new instance.
     *
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * Add data (row) to CSV.
     *
     * @param array $data
     *
     * @return $this
     */
    public function addData(array $data)
    {
        $this->mergeHeader(array_keys($data));
        $this->data[] = $data;

        return $this;
    }

    /**
     * Return built CSV string.
     *
     * @return string
     */
    public function getCsv()
    {
        $csv = "";

        // Build header
        $columns = array_keys($this->header);
        $columns = $this->sanitizeColumns($columns);
        $csv .= $this->buildRow($columns);

        foreach ($this->data as $data) {
            $columns = [];
            foreach ($this->header as $key => $default) {
                $columns[] = isset($data[$key]) ? $data[$key] : $default;
            }
            $columns = $this->sanitizeColumns($columns);
            $csv .= $this->buildRow($columns);
        }

        return $csv;
    }

    /**
     * Return CSV data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Add columns to header (if necessary).
     *
     * @param array $keys
     */
    protected function mergeHeader(array $keys)
    {
        foreach ($keys as $key) {
            if (!isset($this->header[$key])) {
                $this->header[$key] = '';
            }
        }
    }
}
