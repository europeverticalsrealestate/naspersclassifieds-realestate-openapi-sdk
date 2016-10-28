<?php
namespace naspersclassifieds\realestate\openapi\query;

class Query
{
    const SORT_ASC = 'asc';
    const SORT_DESC = 'desc';
    protected $sortDirection;
    protected $limit;
    protected $sortBy;
    protected $page;

    /**
     * @param integer $limit
     * @return static
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param integer $page
     * @return static
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @param string $sortBy
     * @return static
     */
    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
        return $this;
    }

    /**
     * @param string $sortDirection
     * @return static
     */
    public function setSortDirection($sortDirection)
    {
        $this->sortDirection = $sortDirection;
        return $this;
    }

    public function __toString()
    {
        $query = '';
        if ($this->limit) {
            $query .= ($query ? '&' : '?') . 'limit=' . $this->limit;
        }
        if ($this->page) {
            $query .= ($query ? '&' : '?') . 'page=' . $this->page;
        }

        if ($this->sortBy) {
            $query .= ($query ? '&' : '?') . 'sortby=' . $this->sortBy;
        }
        if ($this->sortDirection) {
            $query .= ($query ? '&' : '?') . 'sortdirection=' . $this->sortDirection;
        }

        return $query;
    }
}
