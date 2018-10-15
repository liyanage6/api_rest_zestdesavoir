<?php

namespace AppBundle\Controller\User;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SuggestionController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"suggestion"})
     * @Rest\Get("/users/{userId}/suggestions")
     */
    public function suggestionAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository("AppBundle:User")->find($request->get('userId'));
        $places = $em->getRepository("AppBundle:Place")->findAll();

        $preferences = $user->getPreferences();

        $comp = 0;
        $suggestions = [];

        foreach ($places as $place) {

            foreach ($place->getThemes() as $theme){

                foreach ($preferences as $pref) {
                    if ($theme->getName() === $pref->getName()) {
                        $comp = $theme->getValue() * $pref->getValue() + $comp;
                    }
                }
            }
            if ($comp > 25) {
                $suggestions[] = $place;
                $comp = 0;
            }
        }

//        dump($suggestions);die;
        return $suggestions;

    }
}