<?php
declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class NewNotificationsForm
 * @package App\Form
 */
class NewNotificationsForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('json_data', TextareaType::class, [
                'label' => 'Write yours notifications',
                'attr' => [
                    'height' => '600px',
                    'width' => '100%'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Json([
                        'message' => 'Your notifications JSON is invalid!'
                    ])
                ]
            ])
            ->add('pushNotification', SubmitType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'new_notifications_token!@',
          ]
        );
    }
}
