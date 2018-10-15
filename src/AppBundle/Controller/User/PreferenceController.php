<?php

namespace AppBundle\Controller\User;

use AppBundle\Entity\Preference;
use AppBundle\Entity\User;
use AppBundle\Form\PreferenceType;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class PreferenceController extends Controller
{

    /**
     * @param Request $request
     *
     * @Rest\Get("user/{id}/preferences")
     * @Rest\View()
     */
    public function getPreferencesAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository("AppBundle:User")->find($request->get('id'));
        /** @var $user User */

        if (empty($user)) {
            return View::create(["message" => "Preference not found"], Response::HTTP_NOT_FOUND);
        }

        return $user->getPreferences();
    }

    /**
     * @param Request $request
     *
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"preference"})
     * @Rest\Post("/users/{id}/preferences")
     */
    public function postPreferenceAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository("AppBundle:User")
            ->find($request->get('id'));
        /** @var $user User */

        $preference = new Preference();
        $preference->setUser($user);

        $form = $this->createForm(PreferenceType::class, $preference);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em->persist($preference);
            $em->flush();

            return $preference;
        }
        else {
            return $form;
        }
    }
}