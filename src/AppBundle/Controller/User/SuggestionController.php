<?php

namespace AppBundle\Controller\User;

use AppBundle\Entity\Preference;
use AppBundle\Entity\Theme;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SuggestionController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Get("/users/{userId}/suggestions")
     */
    public function suggestionAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository("AppBundle:User")->find($request->get('userId'));
        /** @var $user User */

        $places = $em->getRepository("AppBundle:Place")->findAll();

        if (empty($user)) {
            return View::create(['message' => "User not found !", Response::HTTP_NOT_FOUND]);
        }

        $preferences = $user->getPreferences();

        $comp = 0;
        $suggestions = [];

        foreach ($places as $place) {

            foreach ($place->getThemes() as $theme){

                foreach ($preferences as $pref) {
                    if ($theme->getName() === $pref->getName()) {
                        $comp += $theme->getValue() * $pref->getValue();
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