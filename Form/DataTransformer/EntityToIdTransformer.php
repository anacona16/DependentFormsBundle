<?php

namespace Anacona16\Bundle\DependentFormsBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EntityToIdTransformer implements DataTransformerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var \Doctrine\ORM\UnitOfWork
     */
    protected $unitOfWork;

    /**
     * @param EntityManagerInterface    $entityManager
     * @param string                    $class
     */
    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        $this->entityManager = $entityManager;
        $this->class = $class;
        $this->unitOfWork = $this->entityManager->getUnitOfWork();
    }

    /**
     * Transforms an object to a string (number).
     *
     * @param mixed $entity
     *
     * @return string
     */
    public function transform($entity)
    {
        if (null === $entity || '' === $entity) {
            return 'null';
        }

        if (!is_object($entity)) {
            throw new UnexpectedTypeException($entity, 'object');
        }
        
        if (!$this->unitOfWork->isInIdentityMap($entity)) {
            throw new InvalidConfigurationException('Entities passed to the choice field must be managed');
        }

        return $entity->getId();
    }

    /**
     * Transforms a string (number) to an object.
     *
     * @param mixed $id
     *
     * @return mixed|void
     *
     * @throws TransformationFailedException if object is not found
     */
    public function reverseTransform($id)
    {
        if ('' === $id || null === $id) {
            return;
        }

        if (!is_numeric($id)) {
            throw new UnexpectedTypeException($id, 'numeric'.$id);
        }

        $entity = $this->entityManager->getRepository($this->class)->findOneById($id);

        if ($entity === null) {
            throw new TransformationFailedException(sprintf('The entity with key "%s" could not be found', $id));
        }

        return $entity;
    }
}
