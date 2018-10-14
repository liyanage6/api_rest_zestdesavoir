<?php

namespace AppBundle\Controller\Place;

use AppBundle\Entity\Place;
use AppBundle\Entity\Theme;
use AppBundle\Form\ThemeType;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class ThemeController extends Controller
{
    /**
     * @param Request $request
     *
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"theme"})
     * @Rest\Post("/places/{id}/themes")
     */
    public function postThemesAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $place = $em->getRepository("AppBundle:Place")
            ->find($request->get('id'));
        /** @var $place Place */

        if (empty($place)) {
            return View::create(['message' => 'Place not found !'], Response::HTTP_NOT_FOUND);
        }

        $theme = new Theme();
        $theme->setPlace($place);

        $form = $this->createForm(ThemeType::class, $theme);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em->persist($theme);
            $em->flush();

            return $theme;
        }
        else {
            return $form;
        }

    }
}