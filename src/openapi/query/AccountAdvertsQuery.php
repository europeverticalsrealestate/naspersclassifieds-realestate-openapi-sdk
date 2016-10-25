<?php
namespace naspersclassifieds\realestate\openapi\query;


class AccountAdvertsQuery
{
    private $limit;
    private $page;

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

    public function __toString()
    {
        $query = '';
        if ($this->limit) {
            $query .= ($query ? '&' : '?') . 'limit=' . $this->limit;
        }
        if ($this->page) {
            $query .= ($query ? '&' : '?') . 'page=' . $this->page;
        }
        return $query;
    }
}