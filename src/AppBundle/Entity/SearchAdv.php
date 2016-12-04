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
    private $choice;

    /**
     * @return mixed
     */
    public function getChoice()
    {
        return $this->choice;
    }

    /**
     * @param mixed $choice
     * @return SearchAdv
     */
    public function setChoice($choice)
    {
        $this->choice = $choice;
        return $this;
    }

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
