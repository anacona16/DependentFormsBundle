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

        $entityAlias = $request->request->get('entity_alias');
        $parentId = $request->request->get('parent_id');
        $emptyValue = $request->request->get('empty_value');

        $entities = $this->getParameter('anacona16.dependent_forms');
        $entityInformation = $entities[$entityAlias];

        if ($entityInformation['role'] !== 'IS_AUTHENTICATED_ANONYMOUSLY') {
            if (false === $this->get('security.authorization_checker')->isGranted($entityInformation['role'])) {
                throw new AccessDeniedException();
            }
        }

        $qb = $this->getDoctrine()
            ->getRepository($entityInformation['class'])
            ->createQueryBuilder('e')
            ->where('e.'.$entityInformation['parent_property'].' = :parent_id')
            ->orderBy('e.'.$entityInformation['order_property'], $entityInformation['order_direction'])
            ->setParameter('parent_id', $parentId)
        ;

        if (null !== $entityInformation['callback']) {
            $repository = $qb->getEntityManager()->getRepository($entityInformation['class']);

            if (!method_exists($repository, $entityInformation['callback'])) {
                throw new \InvalidArgumentException(sprintf('Callback function "%s" in Repository "%s" does not exist.', $entityInformation['callback'], get_class($repository)));
            }

            $repository->$entityInformation['callback']($qb);
        }

        $results = $qb->getQuery()->getResult();

        if (empty($results)) {
            return new Response('<option value="">'.$translator->trans($entityInformation['no_result_msg']).'</option>');
        }

        $html = '';
        if ($emptyValue !== false) {
            $html .= '<option value="">'.$translator->trans($emptyValue).'</option>';
        }

        $accesor = $this->get('property_accessor');

        foreach ($results as $result) {
            if ($entityInformation['property']) {
                $resultString = $accesor->getValue($result, $entityInformation['property']);
            } else {
                $resultString = (string) $result;
            }

            $html .= sprintf('<option value="%s">%s</option>', $result->getId(), $resultString);
        }

        return new Response($html);
    }
}
