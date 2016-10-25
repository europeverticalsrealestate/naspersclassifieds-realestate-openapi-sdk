<?php
namespace naspersclassifieds\realestate\openapi\query;


class AccountAdvertsQuery
{
    const STATUS_ACTIVE = 'active';
    const STATUS_WAITING = 'waiting';
    const STATUS_REMOVED = 'archive';

    const SORT_BY_CREATED_AT = 'created_at';
    const SORT_BY_TITLE = 'title';

    const SORT_ASC = 'asc';
    const SORT_DESC = 'desc';

    private $status;
    private $limit;
    private $page;
    private $sortBy;
    private $sortDirection;

    /**
     * @param string $status
     * @return AccountAdvertsQuery
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param integer $limit
     * @return AccountAdvertsQuery
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param integer $page
     * @return AccountAdvertsQuery
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @param string $sortBy
     * @return AccountAdvertsQuery
     */
    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
        return $this;
    }

    /**
     * @param string $sortDirection
     * @return AccountAdvertsQuery
     */
    public function setSortDirection($sortDirection)
    {
        $this->sortDirection = $sortDirection;
        return $this;
    }

    public function __toString()
    {
        $query = '';
        if ($this->status) {
            $query .= ($query ? '&' : '?') . 'status=' . $this->status;
        }
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