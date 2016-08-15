<?php
/**
 * This file is part of the PHP components package.
 *
 * For the full copyright and license information, please view the LICENSE.md file distributed with this source code.
 *
 * @license MIT License
 * @link    https://github.com/ansas/php-component
 */

namespace Ansas\Component\File;

use Exception;
use Generator;
use IteratorAggregate;
use SplFileInfo;
use SplFileObject;
use SplTempFileObject;

/**
 * Class CsvReader
 *
 * @package Ansas\Component\Csv
 * @author  Ansas Meyer <mail@ansas-meyer.de>
 */
class CsvReader implements IteratorAggregate
{
    /**
     * @var SplFileObject CSV file handle
     */
    protected $file;

    /**
     * @var array CSV header
     */
    protected $header;

    /**
     * @var int CSV line
     */
    protected $line;

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
     * CsvToArray constructor.
     *
     * @param string|SplFileInfo|SplFileObject $file
     *
     * @throws Exception
     */
    public function __construct($file)
    {
        if (!$file instanceof SplFileObject) {
            $file = (string) $file;
            $file = new SplFileObject($file);
        }

        $this->file = $file;
        $this->reset();
    }

    /**
     * Create new instance from file name via static method.
     *
     * @param string|SplFileInfo|SplFileObject $file
     *
     * @return CsvReader A new instance
     */
    public static function create($file)
    {
        return new static($file);
    }

    /**
     * Create new instance from string via static method.
     *
     * @param $string
     *
     * @return CsvReader A new instance
     * @throws Exception
     */
    public static function createFromString($string)
    {
        $file = new SplTempFileObject(-1);

        if (null === $file->fwrite($string)) {
            throw new Exception("Cannot create file");
        }

        return new static($file);
    }

    /**
     * Return CSV as complete array (one element per line).
     *
     * @return array
     */
    public function asArray()
    {
        return iterator_to_array($this->getIterator(), false);
    }

    /**
     * Return CSV header as array.
     *
     * @return array
     * @throws Exception
     */
    public function getHeader()
    {
        if (null == $this->header) {
            $header = $this->getNextDataSet();
            if (null === $header) {
                throw new Exception("Cannot retrieve header");
            }
            $this->setHeader($header);
        }

        return $this->header;
    }

    /**
     * Get the iterator.
     *
     * This method implements the IteratorAggregate interface.
     *
     * @return Generator
     * @throws Exception
     */
    public function getIterator()
    {
        $this->reset();

        $header = $this->getHeader();

        while ($data = $this->getNextDataSet()) {
            if (count($header) != count($data)) {
                throw new Exception("Count mismatch in line {$this->getLineNumber()}");
            }
            $set = array_combine($header, $data);
            yield $set;
        }
    }

    /**
     * Fetch CSV elements as array.
     *
     * @return Generator
     */
    public function fetchArray()
    {
        yield from $this->getIterator();
    }

    /**
     * Fetch CSV elements as object.
     *
     * @return Generator
     */
    public function fetchObject()
    {
        foreach ($this->getIterator() as $set) {
            yield (object) $set;
        }
    }

    /**
     * Get current line number in file.
     *
     * @return int
     */
    public function getLineNumber()
    {
        return $this->line;
    }

    /**
     * Reset file.
     *
     * @return $this
     */
    public function reset()
    {
        $this->header = null;
        $this->line = 0;
        $this->file->rewind();

        return $this;
    }

    /**
     * Set CSV delimiter string.
     *
     * @param string $delimiter
     *
     * @return $this
     */
    public function setDelimiter(string $delimiter)
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
    public function setEnclosure(string $enclosure)
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
    public function setEscape(string $escape)
    {
        $this->escape = $escape;

        return $this;
    }

    /**
     * Set CSV header.
     *
     * Useful for CSV files without header line.
     *
     * @param array $header
     *
     * @return $this
     */
    public function setHeader(array $header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get and parse next data set (line).
     *
     * @return array|null
     * @throws Exception
     */
    protected function getNextDataSet()
    {
        $set = $this->file->fgetcsv($this->delimiter, $this->enclosure, $this->escape);

        if (1 === count($set) && null === $set[0]) {
            return null;
        }
        $this->line++;

        return $set;
    }
}
