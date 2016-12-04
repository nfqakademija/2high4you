<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Advertisement;
use AppBundle\Entity\SearchAdv;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Desire;

class HomeController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repAdv = $em->getRepository("AppBundle:Advertisement");
        $offers = $repAdv->findAll();
        $users = [];
        foreach($offers as $off) {
            $users[] = $repAdv->findUserByAdvUserId($off->getUser()); 
        }
        $searchAdv = new SearchAdv();
        $form = $this->createFormBuilder($searchAdv)
            ->add('choice', ChoiceType::class, ['choices'  => ['miestas' => 'City', 'noriu išmokti' => 'Offer', 'galiu pamokint' => 'Desire',], 'label' => 'Pasirinkte:'])
            ->add('searchString', SearchType::class, ['label' => 'įveskite:'])
            ->add('save', SubmitType::class, ['label' => 'Ieškoti skelbimų',])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $searchAdv = $form->getData();
            $repSearch = $this->get('app_bundle.search_repository');
            $repSearch->setAdvsAndUsers($searchAdv->getSearchString(), $searchAdv->getChoice());
            $offers = $repSearch->getAdvs();
            $users = $repSearch->getUsers();

        }

        return $this->render(
            'AppBundle:Home:index.html.twig', [
            'offers' => $offers,
            'users' => $users,
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/details/{id}", name="offer_details")
     */
    public function detailsAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository("AppBundle:Advertisement");
        $adv = $rep->find($id);
        $user = $rep->findUserByAdvUserId($adv->getUser());

        return $this->render(
            'AppBundle:Home:details.html.twig', [
            'user' => $user,
            'adv' => $adv,
            ]
        );
    }

    /**
     * @Route("/new_user", name="newUser")
     */
    public function newUserAction(Request $request)
    {
        $session = $request->getSession();

        if (!$session->has('user_id')) {
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
                $session->set('user_id', $user->getId());

                return $this->redirectToRoute('newAdv');
            }


            return $this->render(
                'AppBundle:Home:new_user.html.twig', [

                'form' => $form->createView(),
                ]
            );
        }
        else {

            return $this->redirectToRoute('newAdv'); 
        }
    }
    /**
     * @Route("/new_adv", name="newAdv")
     */
    public function newAdvAction(Request $request)
    {
        $session = $request->getSession();
        if (!$session->has('adv_id')) {
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
                $em = $this->getDoctrine()->getManager();
                $user = $this->getDoctrine()
                    ->getRepository('AppBundle:User')
                    ->find($session->get('user_id'));
                $adv->setUser($user);
                $em->persist($adv);
                $em->flush();
                $session->set('adv_id', $adv->getId());

                return $this->redirectToRoute('newDes');
            }


            return $this->render(
                'AppBundle:Home:new_adv.html.twig', [

                'form' => $form->createView(),
                ]
            );
        }
        else {
            return $this->redirectToRoute('newDes'); 
        }
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
            $user = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->find($session->get('user_id'));
            $adv = $this->getDoctrine()
                ->getRepository('AppBundle:Advertisement')
                ->find($session->get('adv_id'));
            $des->setUser($user);
            $des->setAdvert($adv);
            $em = $this->getDoctrine()->getManager();
            $em->persist($des);
            $em->flush();
            $session->clear();
            return $this->redirectToRoute('homepage');
        }

            $user = $session->get('user_id');
            $adv = $session->get('adv_id');


            return $this->render(
                'AppBundle:Home:new_des.html.twig', [

                'form' => $form->createView(),
                'user' => $user,
                'adv' => $adv
                ]
            );
    }
}



