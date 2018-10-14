<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Place;
use AppBundle\Form\PlaceType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlaceController extends Controller
{

    /**
     * @param Request $request
     *
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Get("/places")
     */
    public function getPlacesAction (Request $request)
    {
        $places = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository("AppBundle:Place")
            ->findAll();
        /* @var $places Place[] */

        return $places;
    }

    /**
     * @param Place $place
     *
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Get("/places/{id}")
     */
    public function getPlaceAction (Place $place)
    {
        $place = $this->getDoctrine()
            ->getManager()
            ->getRepository("AppBundle:Place")
            ->find($place);

        if(empty($place)) {
            return View::create(['message' => 'Place Not Found'], Response::HTTP_NOT_FOUND);
        }
//        count($place->getPrices());
//        dump($place);die;

        return $place;
    }

    /**
     * @param Request $request
     *
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Put("places/{id}")
     */
    public function putPlaceAction (Request $request)
    {
        $this->updatePlaceAction($request, true);
    }

    /**
     * @param Request $request
     *
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Patch("places/{id}")
     */
    public function patchPlaceAction (Request $request)
    {
        $this->updatePlaceAction($request, false);
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"place"})
     * @Rest\Post("/places")
     */
    public function postPlacesAction (Request $request)
    {
        $place = new Place();

        $form = $this->createForm(PlaceType::class, $place);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($place);
            $em->flush();
        }
        else {
            return $form;
        }
    }

    /**
     * @param Request $request
     *
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT, serializerGroups={"place"})
     * @Rest\Delete("/places/{id}")
     */
    public function removePlacesAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $place = $em->getRepository("AppBundle:Place")
                    ->find($request->get('id'));
        /** @var $place Place */

        $prices = $place->getPrices();

        foreach ($prices as $price){
            $em->remove($price);
        }

        if ($place) {
            $em->remove($place);
            $em->flush();
        }

    }

    public function updatePlaceAction (Request $request, $clearMissing)
    {
        $em = $this->getDoctrine()->getManager();

        $place = $em->getRepository("AppBundle:Place")
            ->find($request->get('id'));
        /** var $place Place */

        if (empty($place)) {
            return View::create(['message' => 'Place Not Found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(PlaceType::class, $place);
        $form->submit($request->request->all(), $clearMissing);


        if ($form->isValid()) {
            $em->merge($place);
            $em->flush();

            return $place;
        }
        else {
            return $form;
        }
    }
}