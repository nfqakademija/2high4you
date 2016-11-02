<?php
/**
 * Created by PhpStorm.
 * User: dejwas
 * Date: 16.10.31
 * Time: 11.57
 */

namespace SandboxBundle\Service;


use SandboxBundle\Event\Events;
use SandboxBundle\Event\PreCreateEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

class AlarmRemoteControlFactory
{
    /**
     * @var EventDispatcher
     * @var Events
     * @var PreCreateEvent
     */

    private $eventDispatcher;

    public function __construct($eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function create()
    {
        $alarmRemoteControl = new AlarmRemoteControl();
        $alarmRemoteControl->setCase('')
            ->setKey('')
            ->setBattery('')
            ->setButton('');
        $this->eventDispatcher->dispatch(Events::PRE_CREATE, new PreCreateEvent($alarmRemoteControl));
    }
}