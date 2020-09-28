<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageNotFoundController extends AbstractController
{

    public function pageNotFoundAction()
    {
        return $this->render('page_not_found/index.html.twig');
    }
}
