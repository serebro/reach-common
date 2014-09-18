<?php

namespace Reach\Memory;

use Reach\PaginatorInterface;

class Paginator implements PaginatorInterface
{

    protected $_limitRows;

    protected $_data;

    protected $_page;

    /**
     * @param array $config
     */
    public function __construct($config)
    {
    }

    /**
     * Set the current page number
     * @param int $page
     */
    public function setCurrentPage($page)
    {
    }

    /**
     * Returns a slice of the resultset to show in the pagination
     * @return stdClass
     */
    public function getPaginate()
    {
    }
}
