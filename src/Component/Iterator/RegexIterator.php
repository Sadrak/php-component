<?php

namespace Ansas\Iterator;

use Iterator;

/**
 * Class RegexIterator
 *
 * This class works exactly like the \RegexIterator class provided by the Standard PHP Library (SPL).
 *
 * The classes only purpose is to save the regex (regular expression) for usage in child classes.
 *
 * @package Ansas\Iterator
 * @author  Ansas Meyer <mail@ansas-meyer.de>
 */
class RegexIterator extends \RegexIterator
{
    /**
     * @var string Regex
     */
    protected $regex;

    /**
     * FilesystemRegexIterator constructor.
     *
     * @param Iterator $iterator
     * @param string   $regex
     */
    public function __construct(Iterator $iterator, string $regex)
    {
        $this->regex = $regex;
        parent::__construct($iterator, $regex);
    }
}
