<?php
/**
 * Created by PhpStorm.
 * User: dejwas
 * Date: 16.10.31
 * Time: 11.58
 */

namespace SandboxBundle\Event;


use Symfony\Component\EventDispatcher\Event;

class PreCreateEvent extends Event
{
    const NAME = 'app.pre_create';

    private $alarmRemoteControl;

    public function __construct($alarmRemoteControl)
    {
        $this->setAlarmRemoteControl($alarmRemoteControl);
    }

    /**
     * @return mixed
     */
    public function getAlarmRemoteControl()
    {
        return $this->alarmRemoteControl;
    }

    /**
     * @param mixed $alarmRemoteControl
     * @return PreCreateEvent
     */
    public function setAlarmRemoteControl($alarmRemoteControl)
    {
        $this->alarmRemoteControl = $alarmRemoteControl;
        return $this;
    }
}