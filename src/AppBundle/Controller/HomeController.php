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
        $advertisement=new Advertisement(50);
        $advertisement->setDescription("test");
        $em=$this->getDoctrine()->getManager();
//        $em->persist($advertisement);
//        $em->flush();
        $offers=$em->getRepository("AppBundle:Advertisement")->findAll();
        var_dump($offers);

        return $this->render('AppBundle:Home:index.html.twig', [
            'offers' => $offers,

//                [
//                [
//                    'id' => 1,
//                    'name' => 'Teach guitar',
//                    'place' => 'LT Kaunas',
//                    'image' => 'http://placehold.it/100x100',
//                ],
//                [
//                    'id' => 2,
//                    'name' => 'Teach programming',
//                    'place' => 'LT Vilnius',
//                    'image' => 'http://placehold.it/100x100',
//                ],
//                [
//                    'id' => 3,
//                    'name' => 'Teach nothing',
//                    'place' => 'LT Kaunas',
//                    'image' => 'http://placehold.it/100x100',
//                ],
//            ],
        ]);
    }

    /**
     * @Route("/details/{id}", name="offer_details")
     */
    public function detailsAction(Request $request, $id)
    {
        $offers = [
            [
                'id'    => 1,
                'name'  => 'Teach guitar',
                'place' => 'LT Kaunas',
                'image' => 'http://placehold.it/100x100',
            ],
            [
                'id'    => 2,
                'name'  => 'Teach programming',
                'place' => 'LT Vilnius',
                'image' => 'http://placehold.it/100x100',
            ],
            [
                'id'    => 3,
                'name'  => 'Teach nothing',
                'place' => 'LT Kaunas',
                'image' => 'http://placehold.it/100x100',
            ],
        ];

        $chosenOffer = [];
        foreach ($offers as $offer) {
            if ($offer['id'] === (int) $id) {
                $chosenOffer = $offer;
                break;
            }
        }

        $exchange = new Exchange();
        $exchange->setRequest('hello');

        $form = $this->createFormBuilder($exchange)
            ->add('request', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Request Exchange'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $exchange = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
//             $em = $this->getDoctrine()->getManager();
//            $em->getRepository('Exchange')->findAll();
//             $em->persist($exchange1);
//             $em->persist($exchange2);
//             $em->flush();

//            return $this->redirectToRoute('/');
        }

        return $this->render('AppBundle:Home:details.html.twig', [
            'id' => $id,
            'offer' => $chosenOffer,
            'form' => $form->createView(),
            'exchange' => $exchange->getRequest(),
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


