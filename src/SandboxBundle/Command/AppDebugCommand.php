<?php
/**
 * Created by PhpStorm.
 * User: dejwas
 * Date: 16.10.31
 * Time: 13.58
 */

namespace SandboxBundle\Command;


use SandboxBundle\Service\AlarmRemoteControl;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AppDebugCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:debug');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $alarmRemoteControl = $this->getContainer()->get('app.alarmremotecontrol');

        $output->writeln($alarmRemoteControl->getKey());
        $output->writeln($alarmRemoteControl->getCase());
        $output->writeln($alarmRemoteControl->getButton());
        $output->writeln($alarmRemoteControl->getBattery());
        $output->writeln('Done.');
    }
}