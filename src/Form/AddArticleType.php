<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AddArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, array('attr' => ['label' => false, 'class' => 'uk-input', 'placeholder' => 'Titre']))
            ->add('introduction', TextType::class, array('attr' => ['label' => false, 'class' => 'uk-input', 'placeholder' => 'Introduction']))
            ->add('online', ChoiceType::class, [
                'label' => false,
                'choices'  => [
                    'Offline' => 0,
                    'Online' => 1,
                ],
                'attr' => [
                    'class' => 'uk-select'
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Upload Image',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'uk-input'],
            ])
            ->add('text', TextAreaType::class, [
                'attr' => ['class' => 'uk-textarea', 'placeholder' => 'Texte'],
            ])
            ->add('keywords', TextType::class, [
                'attr' => ['class' => 'uk-textarea', 'placeholder' => 'Mots-clés'],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (CategoryRepository $repo) {
                    return $repo->createQueryBuilder('c')
                    ->orderBy('c.name', 'ASC');
                },
                'required' => true,
                'label' => false,
                'attr' => ['class' => 'uk-select'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
