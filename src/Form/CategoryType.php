<?php

namespace App\Form;

use App\Entity\Category;
use App\Form\FormListenerFactory;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryType extends AbstractType
{
    public function __construct(private FormListenerFactory $factory){
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'empty_data'=> ''
            ])
            ->add('slug', TextType::class, [
                'required' => false,
                'constraints' => new Sequentially([
                    new Length(min: 5), 
                    new Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                ])
            ])
            ->add('save', SubmitType::class, [
                'label'=> 'Send'
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->factory->autoSlug('name'))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->factory->timeStamps())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
