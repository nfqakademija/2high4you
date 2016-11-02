<?php

namespace SandboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PostController extends Controller
{
    /**
     * @Route("/show")
     */
    public function showAction()
    {
        return $this->render('SandboxBundle:Post:show.html.twig', array(
            // ...
        ));
    }

}
