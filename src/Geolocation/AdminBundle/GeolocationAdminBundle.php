<?php

namespace Geolocation\AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GeolocationAdminBundle extends Bundle {

    public function getParent() {
        return 'FOSUserBundle';
    }

}
