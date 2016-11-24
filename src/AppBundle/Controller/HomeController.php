<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Advertisement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Desire;

class HomeController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository("AppBundle:Advertisement");
        $offers = $rep->findAll();
        $users = array();
        foreach($offers as $off)
         $users[] = $rep->findUserByAdvUserId($off->getUser());
        return $this->render('AppBundle:Home:index.html.twig', [
            'offers' => $offers,
            'users' => $users
        ]);
    }

    /**
     * @Route("/details/{id}", name="offer_details")
     */
    public function detailsAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository("AppBundle:Advertisement");
        $adv = $rep->find($id);
        $user = $rep->findUserByAdvUserId($adv->getUser());

        return $this->render('AppBundle:Home:details.html.twig', [
            'id' => $id,
            'user' => $user,
            'adv' => $adv,
        ]);
    }

    /**
     * @Route("/new_user", name="newUser")
     */
    public function newUserAction(Request $request)
    {
        $session = $request->getSession();

        if (!$session->has('user'))
        {
            $user = new User();
         $form = $this->createFormBuilder($user)
            ->add('firstName', TextType::class, ['label'  => 'Vardas: ',])
            ->add('lastName', TextType::class, ['label'  => 'Pavardė: ',])
            ->add('email', EmailType::class, ['label'  => 'El.paštas: ',])
            ->add('phoneNumber', TextType::class, ['label'  => 'Tel. numeris: ',])
            ->add('city', TextType::class, ['label'  => 'Miestas: ',])
            ->add('country', TextType::class, ['label'  => 'Šalis: ',])
            ->add('save', SubmitType::class, ['label' => 'Išsaugoti...',])
            ->getForm();


         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $session->set('user', $user);

            return $this->redirectToRoute('newAdv');
         }


         return $this->render('AppBundle:Home:new_user.html.twig', [

           'form' => $form->createView(),
         ]);
        }
        else

            return $this->redirectToRoute('newAdv');
    }
    /**
     * @Route("/new_adv", name="newAdv")
     */
    public function newAdvAction(Request $request)
    {
        $session = $request->getSession();
        if (!$session->has('adv'))
        {
            $adv = new Advertisement();
            $form = $this->createFormBuilder($adv)
                ->add('theme', TextType::class, ['label' => 'Tema: ',])
                ->add('description', TextType::class, ['label' => 'Ko galėčiau pamokint: ',])
                ->add('save', SubmitType::class, ['label' => 'Išsaugoti...',])
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $adv = $form->getData();
                $adv->setCreationDate(new \DateTime('now'));
                $adv->setCreationTime(new \DateTime('now'));
                $adv->setUser($session->get('user'));
                $em = $this->getDoctrine()->getManager();
                $em->merge($adv);
                $em->flush();
                $session->set('adv', $adv);

                return $this->redirectToRoute('newDes');
            }


            return $this->render('AppBundle:Home:new_adv.html.twig', [

                'form' => $form->createView(),
            ]);
        }
        else
            return $this->redirectToRoute('newDes');
    }
    /**
     * @Route("/new_des", name="newDes")
     */
    public function newDesAction(Request $request)
    {
        $session = $request->getSession();
            $des = new Desire();
            $form = $this->createFormBuilder($des)
                ->add('description', TextType::class, ['label' => 'Ko norėčiau išmokti: ',])
                ->add('save', SubmitType::class, ['label' => 'Išsaugoti...',])
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $des = $form->getData();
                $des->setUser($session->get('user'));
//                $des->setAdvert($session->get('adv'));
                $em = $this->getDoctrine()->getManager();
                $em->merge($des);
                $em->flush();
                $session->clear();
                return $this->redirectToRoute('homepage');
            }


            return $this->render('AppBundle:Home:new_des.html.twig', [

                'form' => $form->createView(),
            ]);
    }
}



