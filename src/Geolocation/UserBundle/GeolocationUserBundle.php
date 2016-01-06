<?php

namespace Geolocation\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GeolocationUserBundle extends Bundle {

    public function getParent() {
        return 'FOSUserBundle';
    }

}
