<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 16.11.9
 * Time: 14.16
 */

namespace AppBundle\Entity;


class Exchange
{
    private $request;

    /**
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param string $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }
}