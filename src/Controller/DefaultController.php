<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * show a disconnected message
     *
     * @Route ("/", name="default_controller"),
     */
    public function index(): Response
    {
        return $this->render('default/default.html.twig');
    }
}
