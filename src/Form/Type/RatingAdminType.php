<?php
/**
 * Rating admin type.
 */

namespace App\Form\Type;

use App\Entity\Rating;
use App\Entity\Tea;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

/**
 * Class RatingAdminType.
 */
class RatingAdminType extends AbstractType
{
    /**
     * Constructor.
     */
    public function __construct(private Security $security)
    {
    }

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options Form options
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'rating',
            ChoiceType::class,
            [
                'required' => true,
                'choices' => [
                    'no rating' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                    '6' => 6,
                    '7' => 7,
                    '8' => 8,
                    '9' => 9,
                    '10' => 10,
                ],
            ]
        );
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $builder->add(
                'author',
                EntityType::class,
                [
                    'class' => User::class,
                    'choice_label' => function ($user): string {
                        return $user->getEmail();
                    },
                    'label' => 'label.user.email',
                    'placeholder' => 'label.none',
                    'required' => true,
                ]
            );
            $builder->add(
                'tea',
                EntityType::class,
                [
                    'class' => Tea::class,
                    'choice_label' => function ($tea): string {
                        return $tea->getTitle().' ID: '.$tea->getId();
                    },
                    'label' => 'label.title',
                    'placeholder' => 'label.none',
                    'required' => true,
                ]
            );
        }
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Rating::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'rating';
    }
}
