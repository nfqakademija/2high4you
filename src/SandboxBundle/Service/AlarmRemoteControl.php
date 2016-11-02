<?php
/**
 * Created by PhpStorm.
 * User: dejwas
 * Date: 16.10.31
 * Time: 11.45
 */

namespace SandboxBundle\Service;


class AlarmRemoteControl
{
    private $button;

    private $key;

    private $battery;

    private $case;

    /**
     * @return mixed
     */
    public function getButton()
    {
        return $this->button;
    }

    /**
     * @param mixed $button
     * @return AlarmRemoteControl
     */
    public function setButton($button)
    {
        $this->button = $button;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     * @return AlarmRemoteControl
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBattery()
    {
        return $this->battery;
    }

    /**
     * @param mixed $battery
     * @return AlarmRemoteControl
     */
    public function setBattery($battery)
    {
        $this->battery = $battery;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCase()
    {
        return $this->case;
    }

    /**
     * @param mixed $case
     * @return AlarmRemoteControl
     */
    public function setCase($case)
    {
        $this->case = $case;
        return $this;
    }


}