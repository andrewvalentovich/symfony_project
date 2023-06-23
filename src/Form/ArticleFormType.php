<?php

namespace App\Form;

use App\Entity\Article;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;
use Symfony\Component\Validator\Constraints\Length;

class ArticleFormType extends AbstractType
{
    /**
     * @var UserRepository
     */
    private $userRepository;


    /**
     * ArticleFormType constructor.
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'label'   =>  'Название статьи',
                'required'  =>  'false',
                'constraints'   =>  [
                    new Length([
                        'min'   =>  3,
                        'minMessage'    =>  'Заголовок должен содержать минимум 3 символа'
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr'  =>  ['rows' =>  '3'],
                'label'  =>  'Описание статьи',
                'constraints'   =>  [
                    new Length([
                        'min'   =>  100,
                        'minMessage'    =>  'Описание статьи должено содержать минимум 100 символов'
                    ])
                ]
            ])
            ->add('body', TextareaType::class, [
                'attr'  =>  ['rows' =>  '10'],
                'label'  =>  'Содержимое статьи'
            ])
            ->add('publishedAt', DateTimeType::class, [
                'widget'    =>  'single_text',
                'label'   =>  'Выберите дату публикации статьи',
            ])
            ->add('keywords', null, [
                'label'   =>  'Ключевые слова статьи'
            ])
            ->add('author', EntityType::class, [
                'class' =>  User::class,
                'choice_label'  =>  function (User $user) {
                    return sprintf('%s (id: %d)', $user->getFirstName(), $user->getId());
                },
                'label' =>  'Автор статьи',
                'placeholder'   =>  'Выберите автора статьи',
                'choices'   =>  $this->userRepository->findAllSortedByName()
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
