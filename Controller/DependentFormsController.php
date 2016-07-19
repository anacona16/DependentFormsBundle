<?php

namespace Anacona16\Bundle\DependentFormsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;

class DependentFormsController extends Controller
{
    /**
     * This action handler the ajax call for a dependent field type.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getOptionsAction(Request $request)
    {
        $translator = $this->get('translator');

        $entity_alias = $request->request->get('entity_alias');
        $parent_id = $request->request->get('parent_id');
        $empty_value = $request->request->get('empty_value');

        $entities = $this->getParameter('anacona16.dependent_forms');
        $entity_inf = $entities[$entity_alias];

        if ($entity_inf['role'] !== 'IS_AUTHENTICATED_ANONYMOUSLY') {
            if (false === $this->get('security.authorization_checker')->isGranted($entity_inf['role'])) {
                throw new AccessDeniedException();
            }
        }

        $qb = $this->getDoctrine()
            ->getRepository($entity_inf['class'])
            ->createQueryBuilder('e')
            ->where('e.'.$entity_inf['parent_property'].' = :parent_id')
            ->orderBy('e.'.$entity_inf['order_property'], $entity_inf['order_direction'])
            ->setParameter('parent_id', $parent_id)
        ;

        if (null !== $entity_inf['callback']) {
            $repository = $qb->getEntityManager()->getRepository($entity_inf['class']);

            if (!method_exists($repository, $entity_inf['callback'])) {
                throw new \InvalidArgumentException(sprintf('Callback function "%s" in Repository "%s" does not exist.', $entity_inf['callback'], get_class($repository)));
            }

            $repository->$entity_inf['callback']($qb);
        }

        $results = $qb->getQuery()->getResult();

        if (empty($results)) {
            return new Response('<option value="">'.$translator->trans($entity_inf['no_result_msg']).'</option>');
        }

        $html = '';
        if ($empty_value !== false) {
            $html .= '<option value="">'.$translator->trans($empty_value).'</option>';
        }

        $accesor = $this->get('property_accessor');

        foreach ($results as $result) {
            if ($entity_inf['property']) {
                $res = $accesor->getValue($result, $entity_inf['property']);
            } else {
                $res = (string) $result;
            }

            $html .= sprintf('<option value="%d">%s</option>', $result->getId(), $res);
        }

        return new Response($html);
    }
}
