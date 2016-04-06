<?php
/**
 * Created by PhpStorm.
 * User: maxencebeno
 * Date: 06/04/2016
 * Time: 09:51
 */

namespace Geolocation\AdminBundle\Domain\Services;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Geolocation\AdminBundle\Entity\User;
use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Services\DistanceMatrix\DistanceMatrix;
use Ivory\GoogleMap\Services\DistanceMatrix\DistanceMatrixRequest;
use Ivory\GoogleMap\Services\DistanceMatrix\DistanceMatrixResponse;
use Ivory\GoogleMap\Services\DistanceMatrix\DistanceMatrixResponseElement;
use Ivory\GoogleMap\Services\DistanceMatrix\DistanceMatrixResponseRow;
use Widop\HttpAdapter\CurlHttpAdapter;

class CalculateDistanceFromPosition
{
    /** @var  Registry */
    private $doctrine;
    
    private $request;
    private $distanceMatrix;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
        $this->request = new DistanceMatrixRequest();
        $this->distanceMatrix = new DistanceMatrix(new CurlHttpAdapter());
    }

    public function getMinDistance($datas = [], $user) {

        /**
         * @var User $user
         * @var User $userData
         * @var DistanceMatrixResponse $response
         * @var DistanceMatrixResponseRow $item
         * @var DistanceMatrixResponseElement $element
         */
        
        $this->request = new DistanceMatrixRequest();

        $this->distanceMatrix = new DistanceMatrix(new CurlHttpAdapter());

        $min = 999999;
        $minString = "";
        foreach ($datas as $key => $data) {
            if ($key !== "ville" && $key !== "connectedUser") {
                $userData = $data['user'];

                if ($userData->getLongitude() !== null && $userData->getLatitude() !== null && $user->getId() !== $userData->getId()) {
                    $this->request->setOrigins(array(new Coordinate(round($user->getLatitude(), 1), round($user->getLongitude(), 1), true)));
                    $this->request->setDestinations(array(new Coordinate(round($userData->getLatitude(), 1), round($userData->getLongitude(), 1), true)));

                    $response = $this->distanceMatrix->process($this->request);

                    if (count($response->getRows()) > 0) {
                        foreach ($response->getRows() as $item) {

                            $elements = $item->getElements();
                            foreach ($elements as $element) {
                                $distance = $element->getDistance()->getValue();
                                if ($distance < $min) {
                                    $min = $distance;
                                    $minString = $element->getDistance()->getText();
                                }
                            }
                        }
                    }
                }
            }
        }
        return $min !== 999999 && $minString !== "" ? ['value' => $min, 'string' => $minString] : ['value' => 0, 'string' => "0 km"];
    }

    public function getMaxDistance($datas = [], $user) {

        /**
         * @var User $user
         * @var User $userData
         * @var DistanceMatrixResponse $response
         * @var DistanceMatrixResponseRow $item
         * @var DistanceMatrixResponseElement $element
         */

        $this->request = new DistanceMatrixRequest();

        $this->distanceMatrix = new DistanceMatrix(new CurlHttpAdapter());

        $max = 0;
        $maxString = "";
        foreach ($datas as $key => $data) {
            if ($key !== "ville" && $key !== "connectedUser") {
                $userData = $data['user'];

                if ($userData->getLongitude() !== null && $userData->getLatitude() !== null && $user->getId() !== $userData->getId()) {
                    $this->request->setOrigins(array(new Coordinate(round($user->getLatitude(), 1), round($user->getLongitude(), 1), true)));
                    $this->request->setDestinations(array(new Coordinate(round($userData->getLatitude(), 1), round($userData->getLongitude(), 1), true)));

                    $response = $this->distanceMatrix->process($this->request);

                    if (count($response->getRows()) > 0) {
                        foreach ($response->getRows() as $item) {

                            $elements = $item->getElements();
                            foreach ($elements as $element) {
                                $distance = $element->getDistance()->getValue();
                                if ($distance > $max) {
                                    $max = $distance;
                                    $maxString = $element->getDistance()->getText();
                                }
                            }
                        }
                    }
                }
            }
        }
        return $max !== 0 && $maxString !== "" ? ['value' => $max, 'string' => $maxString] : ['value' => 0, 'string' => "0 km"];
    }
}