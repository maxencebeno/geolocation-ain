<?php

namespace Geolocation\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Index controller.
 *
 */
class IndexController extends Controller {

    public function indexAction() {
        return $this->redirectToRoute('user');
    }

}