<?php

namespace SandboxBundle\Event;
use SandboxBundle\Service\AlarmRemoteControl;

/**
 * Created by PhpStorm.
 * User: dejwas
 * Date: 16.10.31
 * Time: 11.57
 */
class EventListener
{
    /**
     * @param PreCreateEvent $event
     */
    public function makeChanges($event)
    {
        /** @var AlarmRemoteControl $alarmRemoteControl */
        $alarmRemoteControl = $event->getAlarmRemoteControl();
        $alarmRemoteControl->setButton('Alarm button working')
                            ->setBattery('Battery inserted')
                            ->setCase('Case added')
                            ->setKey('Key made');
    }
}