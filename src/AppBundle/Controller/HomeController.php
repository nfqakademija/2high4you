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
        $advs = $repAdv->findByStatus('enabled');
        $repUser = $em->getRepository("AppBundle:User");
        $users = [];
        foreach ($advs as $a) {
            $users[] = $repUser->findOneById($a->getUser());
        }

        $session = $request->getSession();
        $session->set('my_advs', 0);
        $searchAdv = new SearchAdv();
        $form = $this->createFormBuilder($searchAdv)
            ->add(
                'choice',
                ChoiceType::class,
                [
                    'choices'  => [
                        'Miestas' => 'City',
                        'Pasiūlymai' => 'Offer',
                        'Norai' => 'Desire'
                    ],
                    'label' => 'Ieškoti pagal:'
                ]
            )
            ->add('searchString', SearchType::class, ['label' => 'Raktažodis:'])
            ->add('save', SubmitType::class, ['label' => 'Ieškoti skelbimų',])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchAdv = $form->getData();
            $repSearch = $this->get('app_bundle.search_repository');
            $repSearch->setAdvsAndUsers($searchAdv->getSearchString(), $searchAdv->getChoice());
            $users = $repSearch->getUsers();
            $advs = $repSearch->getAdvs();
        }

        return $this->render(
            'AppBundle:Home:index.html.twig',
            [
                'users' => $users,
                'advs' => $advs,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/details/{id}", name="offer_details")
     */
    public function detailsAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $repAdv = $em->getRepository("AppBundle:Advertisement");
        $adv = $repAdv->find($id);
        $repUser = $em->getRepository("AppBundle:User");
        $user = $repUser->findById($adv->getUser());
        if ($session->get('my_advs') === 1) {
            $s = new SearchAdv();
            if ($adv->getStatus() === 'enabled') {
                $form = $this->createFormBuilder($s)
                    ->add('save', SubmitType::class, ['label' => 'Paslėpti skelbimą',])
                    ->add('delete', SubmitType::class, array('label' => 'Panaikinti skelbimą'))
                    ->getForm();
            } else {
                $form = $this->createFormBuilder($s)
                    ->add('save', SubmitType::class, ['label' => 'Aktyvuoti skelbimą',])
                    ->add('delete', SubmitType::class, array('label' => 'Panaikinti skelbimą'))
                    ->getForm();
            }
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('delete')->isClicked()) {
                    $repDes = $em->getRepository("AppBundle:Desire");
                    $des = $repDes->findByAdvert($adv);
                    foreach ($des as $d) {
                        $em->remove($d);
                    }
                    $em->remove($adv);
                    $em->flush();

                    return $this->redirectToRoute('myAdvs');
                } else {
                    if ($adv->getStatus() === 'enabled') {
                        $adv->setStatus('disabled');
                    } else {
                        $adv->setStatus('enabled');
                    }
                    $em->flush();
                    if ($adv->getStatus() === 'enabled') {
                        $form = $this->createFormBuilder($s)
                            ->add('save', SubmitType::class, ['label' => 'Paslėpti skelbimą',])
                            ->add('delete', SubmitType::class, array('label' => 'Panaikinti skelbimą'))
                            ->getForm();
                    } else {
                        $form = $this->createFormBuilder($s)
                            ->add('save', SubmitType::class, ['label' => 'Aktyvuoti skelbimą',])
                            ->add('delete', SubmitType::class, array('label' => 'Panaikinti skelbimą'))
                            ->getForm();
                    }
                }
            }

            return $this->render(
                'AppBundle:Home:details.html.twig',
                [
                    'user' => $user[0],
                    'adv' => $adv,
                    'form' => $form->createView(),
                    'f' => 1,
                ]
            );
        }

        return $this->render(
            'AppBundle:Home:details.html.twig',
            [
                'user' => $user[0],
                'adv' => $adv,
                'f' => 0,
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
                ->add('save', SubmitType::class, ['label' => 'Išsaugoti',])
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
                'AppBundle:Home:new_user.html.twig',
                [
                    'form' => $form->createView(),
                ]
            );
        } else {
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
                ->add('theme', ChoiceType::class,
                    [
                        'choices'  => [
                            'Menas' => 'Art',
                            'Mokslas' => 'Study',
                            'Muzika' => 'Music'
                        ],
                        'label' => 'Tema:'
                    ])
                ->add('description', TextType::class, ['label' => 'Mokau: ',])
                ->add('save', SubmitType::class, ['label' => 'Išsaugoti',])
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $adv = $form->getData();
                $adv->setCreationDate(new \DateTime('now'));
                $adv->setCreationTime(new \DateTime('now'));
                $adv->setStatus('enabled');
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
                'AppBundle:Home:new_adv.html.twig',
                [
                    'form' => $form->createView(),
                ]
            );
        } else {
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
                ->add('description', TextType::class, ['label' => 'Norėčiau išmokti: ',])
                ->add('save', SubmitType::class, ['label' => 'Išsaugoti',])
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
                'AppBundle:Home:new_des.html.twig',
                [
                    'form' => $form->createView(),
                    'user' => $user,
                    'adv' => $adv
                ]
            );
    }

    /**
     * @Route("/my_advs", name="myAdvs")
     */
    public function myAdvsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repUser = $em->getRepository("AppBundle:User");
        $user = $repUser->findById(4);
        $session = $request->getSession();
        $session->set('my_advs', 1);

        return $this->render(
            'AppBundle:Home:my_advs.html.twig',
            [
                'user' => $user[0],
            ]
        );
    }
}
