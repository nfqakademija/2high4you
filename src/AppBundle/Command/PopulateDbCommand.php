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
        $user->setLogin('jonasp');
        $user->setPsw(password_hash('johny',PASSWORD_DEFAULT));
        $user->setFirstName("Jonas");
        $user->setLastName("Pokštas");
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
        $adv->setStatus("enabled");
        $em->persist($adv);

        $des = new Desire();
        $des->setAdvert($adv);
        $des->setDescription("Noriu išmokti programavimo.");
        $des->setUser($user);
        $em->persist($des);

        $user = new User();
        $user->setLogin('deividasl');
        $user->setPsw(password_hash('deivas',PASSWORD_DEFAULT));
        $user->setFirstName("Deividas");
        $user->setLastName("Lenkus");
        $user->setPhoneNumber("+37064411342");
        $user->setEmail("d.lenkus@gmail.com");
        $user->setCity("Šiauliai");
        $user->setCountry("Lietuva");
        $em->persist($user);

        $adv = new Advertisement();
        $adv->setCreationDate(new \DateTime('now'));
        $adv->setCreationTime(new \DateTime('now'));
        $adv->setTheme("Matematika");
        $adv->setUser($user);
        $adv->setDescription("Mokau matematikos.");
        $adv->setStatus("enabled");
        $em->persist($adv);

        $des = new Desire();
        $des->setAdvert($adv);
        $des->setDescription("Noriu išmokti istorijos.");
        $des->setUser($user);
        $em->persist($des);

        $user = new User();
        $user->setLogin('grazvydasj');
        $user->setPsw(password_hash('garzvis',PASSWORD_DEFAULT));
        $user->setFirstName("Gražvydas");
        $user->setLastName("Jovaiša");
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
        $adv->setStatus("enabled");
        $em->persist($adv);

        $des = new Desire();
        $des->setAdvert($adv);
        $des->setDescription("Noriu išmokti kulinarijos.");
        $des->setUser($user);
        $em->persist($des);

        $user = new User();
        $user->setLogin('test');
        $user->setPsw(password_hash('test',PASSWORD_DEFAULT));
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
        $adv->setTheme("Šokiai");
        $adv->setUser($user);
        $adv->setDescription("Mokau šokti.");
        $adv->setStatus("enabled");
        $em->persist($adv);

        $des = new Desire();
        $des->setAdvert($adv);
        $des->setDescription("Noriu išmokti žaisti šachmatais.");
        $des->setUser($user);
        $em->persist($des);

        $adv = new Advertisement();
        $adv->setCreationDate(new \DateTime('now'));
        $adv->setCreationTime(new \DateTime('now'));
        $adv->setTheme("Bėgimas");
        $adv->setUser($user);
        $adv->setDescription("Vedu bėgimo treniruotes.");
        $adv->setStatus("enabled");
        $em->persist($adv);

        $des = new Desire();
        $des->setAdvert($adv);
        $des->setDescription("Noriu išmokti žaisti krepšinį.");
        $des->setUser($user);
        $em->persist($des);
        $em->flush();

        $output->writeln("Database populated!");
    }

}
