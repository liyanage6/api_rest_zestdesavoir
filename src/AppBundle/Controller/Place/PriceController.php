<?php

namespace AppBundle\Controller\Place;

use AppBundle\Entity\Place;
use AppBundle\Entity\Price;
use AppBundle\Form\PriceType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PriceController extends Controller
{
    /**
     * @param Request $request
     *
     * @Rest\View(serializerGroups={"price"})
     * @Rest\Get("/places/{id}/prices")
     */
    public function getPricesAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $places = $em->getRepository("AppBundle:Place")
                        ->find($request->get('id'));


        if (empty($places)) {
            $this->placeNotFound();
        }

//        count($places->getPrices());
//        dump($places->getPrices());die;

        return $places->getPrices();
    }

    /**
     * @param Request $request
     *
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/places/{id}/prices")
     */
    public function postPricesAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $place = $em->getRepository("AppBundle:Place")
                    ->find($request->get('id'));
        /** @var $place Place */

        if (empty($place)) {
            return $this->placeNotFound();
        }

        $price = new Price();
        $price->setPlace($place); // Ici, le lieu est associé au prix

        $form = $this->createForm(PriceType::class, $price);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em->persist($price);
            $em->flush();

            return $price;
        }
        else {
            return $form;
        }
    }

    public function placeNotFound ()
    {
        return View::create(['message' => 'Price Not Found'], Response::HTTP_NOT_FOUND);
    }
}