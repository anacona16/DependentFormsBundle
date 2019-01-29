<?php

namespace Anacona16\Bundle\DependentFormsBundle\Form\Type;

use Anacona16\Bundle\DependentFormsBundle\Form\DataTransformer\EntityToIdTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DependentFormsType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */

    private $entityManager;
    /**
     * @var ParameterBag
     */
    private $parameterBag;

    /**
     * @param EntityManagerInterface    $entityManager
     * @param ParameterBagInterface     $parameterBag
     */
    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
        $this->entityManager = $entityManager;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'empty_value' => '',
            'entity_alias' => null,
            'parent_field' => null,
            'show_loading' => true,
            'compound' => false,
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entities = $this->parameterBag->get('anacona16.dependent_forms');

        $options['class'] = $entities[$options['entity_alias']]['class'];
        $options['property'] = $entities[$options['entity_alias']]['property'];

        $options['no_result_msg'] = $entities[$options['entity_alias']]['no_result_msg'];

        $builder->addViewTransformer(new EntityToIdTransformer(
            $this->entityManager,
            $options['class']
        ), true);

        $builder->setAttribute('parent_field', $options['parent_field']);
        $builder->setAttribute('entity_alias', $options['entity_alias']);
        $builder->setAttribute('no_result_msg', $options['no_result_msg']);
        $builder->setAttribute('show_loading', $options['show_loading']);
        $builder->setAttribute('empty_value', $options['empty_value']);
    }

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['parent_field'] = $form->getConfig()->getAttribute('parent_field');
        $view->vars['entity_alias'] = $form->getConfig()->getAttribute('entity_alias');
        $view->vars['no_result_msg'] = $form->getConfig()->getAttribute('no_result_msg');
        $view->vars['show_loading'] = $form->getConfig()->getAttribute('show_loading');
        $view->vars['empty_value'] = $form->getConfig()->getAttribute('empty_value');
    }

    public function getParent()
    {
        return FormType::class;
    }
}
