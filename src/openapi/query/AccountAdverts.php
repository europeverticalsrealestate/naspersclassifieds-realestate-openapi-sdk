<?php
namespace naspersclassifieds\realestate\openapi\query;


class AccountAdverts extends Query
{
    const STATUS_ACTIVE = 'active';
    const STATUS_WAITING = 'waiting';
    const STATUS_REMOVED = 'archive';

    const SORT_BY_CREATED_AT = 'created_at';
    const SORT_BY_TITLE = 'title';


    private $status;

    /**
     * @param string $status
     * @return static
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function __toString()
    {
        $query = parent::__toString();

        if ($this->status) {
            $query .= ($query ? '&' : '?') . 'status=' . $this->status;
        }

        return $query;
    }
}