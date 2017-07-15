<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Entity\usuario;
use AppBundle\Form\telefonoType;

class personaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('firstname',TextType::class, array('attr' =>array('class' => 'form-control input-lg', 'style' => 'margin-bottom: 15px', 'placeholder' => 'Nombre'),'label' => 'firstName'))
            ->add('lastname',TextType::class, array('attr' =>array('class' => 'form-control input-lg', 'style' => 'margin-bottom: 15px', 'placeholder' => 'Apellido'),'label' => 'lastname'))
            ->add('birth',DateType::class, array('widget' => 'single_text',
                'format' => 'yyyy-MM-dd','attr' =>array('class' => 'form-control input-lg', 'style' => 'margin-bottom: 15px', 'placeholder' => 'Nacimiento'),'label' => 'Nacimiento'))
            ->add('age',TextType::class, array('attr' =>array('class' => 'form-control input-lg', 'style' => 'margin-bottom: 15px', 'placeholder' => 'Edad'),'label' => 'age'))
            ->add('telefono', telefonoType::class)
            #->add('phone',TextType::class, array('mapped' => false, 'attr' =>array('class' => 'form-control input-lg', 'style' => 'margin-bottom: 15px', 'placeholder' => 'Telefono'),'label' => 'age'))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\persona'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_persona';
    }


}
