<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 16.12.6
 * Time: 20.46
 */

namespace AppBundle\Entity;

class LogIn
{
    /**
     * @var String
     */
    private $login;
    /**
     * @var String
     */
    private $psw;

    /**
     * @return String
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param String $login
     * @return LogIn
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return String
     */
    public function getPsw()
    {
        return $this->psw;
    }

    /**
     * @param String $psw
     * @return LogIn
     */
    public function setPsw($psw)
    {
        $this->psw = $psw;
        return $this;
    }
}
