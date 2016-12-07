<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Advertisement;
use AppBundle\Entity\LogIn;
use AppBundle\Entity\SearchAdv;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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

        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $repAdv = $em->getRepository("AppBundle:Advertisement");
        $advs = $repAdv->findByStatus('enabled');
        $repUser = $em->getRepository("AppBundle:User");
        if($session->has('user_id'))
            $logedin = 1;
        else
            $logedin = 0;
        $users = [];
        foreach ($advs as $a) {
            $users[] = $repUser->findOneById($a->getUser());
        }

        $session->set('my_advs', 0);
        $searchAdv = new SearchAdv();
        $form = $this->createFormBuilder($searchAdv)
            ->add(
                'choice',
                ChoiceType::class,
                [
                    'choices' => [
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
                'logedin' => $logedin,
            ]
        );
    }

    /**
     * @Route("/details/{id}", name="offer_details")
     */
    public function detailsAction(Request $request, $id)
    {

        $session = $request->getSession();
        if(!$session->has('user_id'))
            return $this->redirectToRoute('login');
        $em = $this->getDoctrine()->getManager();
        $repAdv = $em->getRepository("AppBundle:Advertisement");
        $adv = $repAdv->find($id);
        $repUser = $em->getRepository("AppBundle:User");
        $user = $repUser->findOneById($adv->getUser());
        $logedin = 0;
        if ($session->has('user_id')) {
            $logedin = 1;
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
                    'user' => $user,
                    'adv' => $adv,
                    'form' => $form->createView(),
                    'f' => $session->get('my_advs'),
                    'logedin' => $logedin,
                ]
            );
        }

        return $this->render(
            'AppBundle:Home:details.html.twig',
            [
                'user' => $user,
                'adv' => $adv,
                'f' => $session->get('my_advs'),
                'logedin' => $logedin,
            ]
        );
    }

    /**
     * @Route("/new_adv", name="newAdv")
     */
    public function newAdvAction(Request $request)
    {
        $session = $request->getSession();
        if(!$session->has('user_id'))
            return $this->redirectToRoute('login');
        if (!$session->has('adv_id')) {
            $adv = new Advertisement();
            $form = $this->createFormBuilder($adv)
                ->add('theme', TextType::class, ['label' => 'Tema:',])
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
        if(!$session->has('user_id'))
            return $this->redirectToRoute('login');
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
            $session->remove('adv_id');
            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'AppBundle:Home:new_des.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/my_advs", name="myAdvs")
     */
    public function myAdvsAction(Request $request)
    {
        $session = $request->getSession();
        if(!$session->has('user_id'))
            return $this->redirectToRoute('login');
        $em = $this->getDoctrine()->getManager();
        $repUser = $em->getRepository("AppBundle:User");
        $user = $repUser->findOneById($session->get('user_id'));
        $session = $request->getSession();
        $session->set('my_advs', 1);

        return $this->render(
            'AppBundle:Home:my_advs.html.twig',
            [
                'user' => $user,
            ]
        );
    }


    /**
     * @Route("/register", name="registration")
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('login', TextType::class, ['label' => 'Prisijungimo vardas: ',])
            ->add('psw', PasswordType::class, ['label' => 'Slaptažodis: ',])
            ->add('firstName', TextType::class, ['label' => 'Vardas: ',])
            ->add('lastName', TextType::class, ['label' => 'Pavardė: ',])
            ->add('email', EmailType::class, ['label' => 'El.paštas: ',])
            ->add('phoneNumber', TextType::class, ['label' => 'Tel. numeris: ',])
            ->add('city', TextType::class, ['label' => 'Miestas: ',])
            ->add('country', TextType::class, ['label' => 'Šalis: ',])
            ->add('save', SubmitType::class, ['label' => 'Išsaugoti',])
            ->getForm();

        $message = "";
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $repUser = $em->getRepository("AppBundle:User");
            $us = $repUser->findOneByLogin($user->getLogIn());
            if($us)
            {
                $message = 'Toks prisijungimo vardas jau egzistuoja. Bandykite registruotis kitu vardu.';
                return $this->render('AppBundle:Home:register.html.twig',
                    [
                        'form' => $form->createView(),
                        'message' => $message,
                    ]);
            }
            else
            {
                $em->persist($user);
                $em->flush();
                $session = $request->getSession();
                $session->set('user_id', $user->getId());
                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('AppBundle:Home:register.html.twig',
            [
                'form' => $form->createView(),
                'message' => $message,
            ]);

    }
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $log = new LogIn();
        $form = $this->createFormBuilder($log)
            ->add('login', TextType::class, ['label' => 'Prisijungimo vardas: ',])
            ->add('psw', PasswordType::class, ['label' => 'Slaptažodis: ',])
            ->add('save', SubmitType::class, ['label' => 'Prisijungti',])
            ->getForm();
        $message = '';

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $log = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $repUser = $em->getRepository("AppBundle:User");
            $user = $repUser->findOneByLogin($log->getLogin());
            if($user)
            {
                if($user->getPsw() === $log->getPsw())
                {
                    $session = $request->getSession();
                    $session->set('user_id', $user->getId());
                    return $this->redirectToRoute('homepage');
                }
                else
                {
                    $message = 'Nurodytas slaptažodis neteisingas!';
                    return $this->render('AppBundle:Home:login.html.twig',
                        [
                            'form' => $form->createView(),
                            'message' => $message,
                        ]);
                }
            }
            else
            {
                $message = 'Vartotojas nurodytu prisijungimo vardu neegzistuoja!';
                return $this->render('AppBundle:Home:login.html.twig',
                    [
                        'form' => $form->createView(),
                        'message' => $message,
                    ]);
            }

        }

        return $this->render('AppBundle:Home:login.html.twig',
            [
                'form' => $form->createView(),
                'message' => $message,
            ]
        );

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request)
    {
        $session = $request->getSession();
        if(!$session->has('user_id'))
            return $this->redirectToRoute('login');
        $session->clear();
        return $this->redirectToRoute('homepage');

    }

    /**
     * @Route("/unregister", name="unregister")
     */
    public function unregisterAction(Request $request)
    {
        $session = $request->getSession();
        if(!$session->has('user_id'))
            return $this->redirectToRoute('login');
        $em = $this->getDoctrine()->getManager();
        $repUser = $em->getRepository("AppBundle:User");
        $user = $repUser->find($session->get('user_id'));
        $em->remove($user);
        $em->flush();
        $session->clear();
        return $this->redirectToRoute('homepage');

    }
}

