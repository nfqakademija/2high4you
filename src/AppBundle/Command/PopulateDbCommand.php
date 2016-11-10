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
use AppBundle\Entity\Offer;
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

        $user = new User(1);
        $user->setFirstName("Jonas");
        $user->setLastName("Pokstas");
        $user->setPhoneNumber("+37060646561");
        $user->setEmail("j.pokstas@gmail.com");
        $user->setCity("Kaunas");
        $user->setCountry("Lietuva");
        $em->persist($user);

        $adv = new Advertisement(1);
        $adv->setCreationDate(new \DateTime('now'));
        $adv->setCreationTime(new \DateTime('now'));
        $adv->setTheme("Menas");
        $adv->setUserId($user->getId());
        $adv->setDescription("Mokau tapybos.");
        $em->persist($adv);

        $des = new Desire(1);
        $des->setAdvId($adv->getId());
        $des->setDescription("Noriu ismokti programavimo.");
        $em->persist($des);

        $user = new User(2);
        $user->setFirstName("Deividas");
        $user->setLastName("Lenkus");
        $user->setPhoneNumber("+37064411342");
        $user->setEmail("d.lenkus@gmail.com");
        $user->setCity("Kaunas");
        $user->setCountry("Lietuva");
        $em->persist($user);

        $adv = new Advertisement(2);
        $adv->setCreationDate(new \DateTime('now'));
        $adv->setCreationTime(new \DateTime('now'));
        $adv->setTheme("Matematika");
        $adv->setUserId($user->getId());
        $adv->setDescription("Mokau matematikos.");
        $em->persist($adv);

        $des = new Desire(2);
        $des->setAdvId($adv->getId());
        $des->setDescription("Noriu ismokti istorijos.");
        $em->persist($des);


        $user = new User(3);
        $user->setFirstName("Grazvydas");
        $user->setLastName("Jovaisa");
        $user->setPhoneNumber("+37064411300");
        $user->setEmail("g.jovaisa@gmail.com");
        $user->setCity("Kaunas");
        $user->setCountry("Lietuva");
        $em->persist($user);


        $adv = new Advertisement(3);
        $adv->setCreationDate(new \DateTime('now'));
        $adv->setCreationTime(new \DateTime('now'));
        $adv->setTheme("Vairavimas");
        $adv->setUserId($user->getId());
        $adv->setDescription("Mokau vairavimo.");
        $em->persist($adv);

        $des = new Desire(3);
        $des->setAdvId($adv->getId());
        $des->setDescription("Noriu ismokti kulinarijos.");
        $em->persist($des);

        $user = new User(4);
        $user->setFirstName("Andrius");
        $user->setLastName("Buivydas");
        $user->setPhoneNumber("+37065411312");
        $user->setEmail("a.buivydas@gmail.com");
        $user->setCity("Kaunas");
        $user->setCountry("Lietuva");
        $em->persist($user);


        $adv = new Advertisement(4);
        $adv->setCreationDate(new \DateTime('now'));
        $adv->setCreationTime(new \DateTime('now'));
        $adv->setTheme("Sokiai");
        $adv->setUserId($user->getId());
        $adv->setDescription("Mokau sokti.");
        $em->persist($adv);

        $des = new Desire(4);
        $des->setAdvId($adv->getId());
        $des->setDescription("Noriu ismokti zaisti sachmatais.");
        $em->persist($des);

        $des = new Desire(5);
        $des->setAdvId($adv->getId());
        $des->setDescription("Noriu ismokti zaisti saskemis.");
        $em->persist($des);

        $adv = new Advertisement(5);
        $adv->setCreationDate(new \DateTime('now'));
        $adv->setCreationTime(new \DateTime('now'));
        $adv->setTheme("Begimas");
        $adv->setUserId($user->getId());
        $adv->setDescription("Vedu begimo treniruotes.");
        $em->persist($adv);

        $des = new Desire(6);
        $des->setAdvId($adv->getId());
        $des->setDescription("Noriu ismokti zaisti krepsini.");
        $em->persist($des);



        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        $output->writeln("Database populated!");
    }

}