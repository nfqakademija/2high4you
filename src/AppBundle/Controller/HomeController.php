<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Advertisement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use AppBundle\Entity\Exchange;
use Symfony\Component\HttpFoundation\Request;

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
//        $exchange = new Exchange();
//        $exchange->setRequest('hello');
//
//        $form = $this->createFormBuilder($exchange)
//            ->add('request', TextType::class)
//            ->add('save', SubmitType::class, ['label' => 'Request Exchange'])
//            ->getForm();
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            // $form->getData() holds the submitted values
//            // but, the original `$task` variable has also been updated
//            $exchange = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
//             $em = $this->getDoctrine()->getManager();
//            $em->getRepository('Exchange')->findAll();
//             $em->persist($exchange1);
//             $em->persist($exchange2);
//             $em->flush();

//            return $this->redirectToRoute('/');
//        }

        return $this->render('AppBundle:Home:details.html.twig', [
            'id' => $id,
            'user' => $user,
            'adv' => $adv,
            //'form' => $form->createView(),
            //'exchange' => $exchange->getRequest(),
        ]);
    }

    /**
     * @Route("/list", name="posts_list")
     */
    public function listAction()
    {
        $exampleService = $this->get('app.example');

        $posts = $exampleService->getPosts();

        return $this->render('AppBundle:Home:list.html.twig', [
            'posts' => $posts,
        ]);
    }
}


