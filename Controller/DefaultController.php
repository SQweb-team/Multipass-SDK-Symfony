<?php

namespace VendorSQweb\SQwebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('VendorSQwebSQwebBundle:Default:index.html.twig');
    }
}
