<?php

namespace SQweb\SQwebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SQwebSQwebBundle:Default:index.html.twig');
    }
}
