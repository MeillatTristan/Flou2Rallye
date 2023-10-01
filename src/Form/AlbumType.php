<?php

namespace App\Form;

use App\Entity\Album;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom de l'album",
                'required' => true,
            ])
            ->add('private', CheckboxType::class, [
                'label' => "L'album est-il privé ?"
            ])
            ->add('categories', HiddenType::class, [
                "mapped" => false,
                'label' => "Ajouter des catégories"
            ])
            ->add('multipleFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => "Ajout de plusieurs photos",
                'multiple' => true,
            ])
            ->add('photos', CollectionType::class, [
                'entry_type' => PhotoType::class,
                'label' => "Photos de l'album",
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_options' => [
                    'label' => false
                ]
            ])
            ->add('coverFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'download_label' => 'Télécharger',
                'download_uri' => true,
                'image_uri' => true,
                'imagine_pattern' => 'thumb_edit_album',
                'asset_helper' => true,
                'label' => "Ajout de la photo de bannière"
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
