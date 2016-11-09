<?php



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