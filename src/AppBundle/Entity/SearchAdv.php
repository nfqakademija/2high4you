<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 16.12.2
 * Time: 12.19
 */

namespace AppBundle\Entity;


class SearchAdv
{
    private $searchString;

    /**
     * @return mixed
     */
    public function getSearchString()
    {
        return $this->searchString;
    }

    /**
     * @param mixed $searchString
     * @return SearchAdv
     */
    public function setSearchString($searchString)
    {
        $this->searchString = $searchString;
        return $this;
    }

}