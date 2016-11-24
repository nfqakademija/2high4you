<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 16.11.9
 * Time: 23.01
 */

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\User;
use AppBundle\Entity\Advertisement;
use AppBundle\Entity\Desire;

class PopulateDbCommand extends ContainerAwareCommand
{
    protected function configure()
    {

        $this
            // the name of the command (the part after "bin/console")
            ->setName('PopulateDb')

            // the short description shown while running "php bin/console list"
            ->setDescription('Populates database.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("Populates database.")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $user = new User();
        $user->setFirstName("Jonas");
        $user->setLastName("Pokstas");
        $user->setPhoneNumber("+37060646561");
        $user->setEmail("j.pokstas@gmail.com");
        $user->setCity("Kaunas");
        $user->setCountry("Lietuva");
        $em->persist($user);

        $adv = new Advertisement();
        $adv->setUser($user);
        $adv->setCreationDate(new \DateTime('now'));
        $adv->setCreationTime(new \DateTime('now'));
        $adv->setTheme("Menas");
        $adv->setDescription("Mokau tapybos.");
        $em->persist($adv);

        $des = new Desire();
        $des->setAdvert($adv);
        $des->setDescription("Noriu ismokti programavimo.");
        $des->setUser($user);
        $em->persist($des);

        $user = new User();
        $user->setFirstName("Deividas");
        $user->setLastName("Lenkus");
        $user->setPhoneNumber("+37064411342");
        $user->setEmail("d.lenkus@gmail.com");
        $user->setCity("Siauliai");
        $user->setCountry("Lietuva");
        $em->persist($user);

        $adv = new Advertisement();
        $adv->setCreationDate(new \DateTime('now'));
        $adv->setCreationTime(new \DateTime('now'));
        $adv->setTheme("Matematika");
        $adv->setUser($user);
        $adv->setDescription("Mokau matematikos.");
        $em->persist($adv);

        $des = new Desire();
        $des->setAdvert($adv);
        $des->setDescription("Noriu ismokti istorijos.");
        $des->setUser($user);
        $em->persist($des);

        $user = new User();
        $user->setFirstName("Grazvydas");
        $user->setLastName("Jovaisa");
        $user->setPhoneNumber("+37064411300");
        $user->setEmail("g.jovaisa@gmail.com");
        $user->setCity("Vilnius");
        $user->setCountry("Lietuva");
        $em->persist($user);


        $adv = new Advertisement();
        $adv->setCreationDate(new \DateTime('now'));
        $adv->setCreationTime(new \DateTime('now'));
        $adv->setTheme("Vairavimas");
        $adv->setUser($user);
        $adv->setDescription("Mokau vairavimo.");
        $em->persist($adv);

        $des = new Desire();
        $des->setAdvert($adv);
        $des->setDescription("Noriu ismokti kulinarijos.");
        $des->setUser($user);
        $em->persist($des);

        $user = new User();
        $user->setFirstName("Andrius");
        $user->setLastName("Buivydas");
        $user->setPhoneNumber("+37065411312");
        $user->setEmail("a.buivydas@gmail.com");
        $user->setCity("Kaunas");
        $user->setCountry("Lietuva");
        $em->persist($user);


        $adv = new Advertisement();
        $adv->setCreationDate(new \DateTime('now'));
        $adv->setCreationTime(new \DateTime('now'));
        $adv->setTheme("Sokiai");
        $adv->setUser($user);
        $adv->setDescription("Mokau sokti.");
        $em->persist($adv);

        $des = new Desire();
        $des->setAdvert($adv);
        $des->setDescription("Noriu ismokti zaisti sachmatais.");
        $des->setUser($user);
        $em->persist($des);

        $des = new Desire();
        $des->setAdvert($adv);
        $des->setDescription("Noriu ismokti zaisti saskemis.");
        $des->setUser($user);
        $em->persist($des);

        $adv = new Advertisement();
        $adv->setCreationDate(new \DateTime('now'));
        $adv->setCreationTime(new \DateTime('now'));
        $adv->setTheme("Begimas");
        $adv->setUser($user);
        $adv->setDescription("Vedu begimo treniruotes.");
        $em->persist($adv);

        $des = new Desire();
        $des->setAdvert($adv);
        $des->setDescription("Noriu ismokti zaisti krepsini.");
        $des->setUser($user);
        $em->persist($des);

        $em->flush();

        $output->writeln("Database populated!");
    }

}