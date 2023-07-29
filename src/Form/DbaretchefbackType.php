<?php

namespace App\Form;

use App\Entity\Dbaretchefback;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class DbaretchefbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('nom')
            ->add('description')
            ->add('temps_preparation')
            ->add('niv_difficulte')
            ->add('nombre_ingredient')
            ->add('photo')
            ->add('video')
            ->add('ingredients')
            ->add('apports_nutritifs')
            ->add('subcategory')
            ->add('photoFile', VichImageType::class, [
                'required' => false, // Set this to true if the photo is mandatory
                'allow_delete' => true,
                'data_class' => null,
                'download_label' => false,
                'download_uri' => true,
                'asset_helper' => true,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dbaretchefback::class,
        ]);
    }
}
