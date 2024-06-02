<?php
/**
 * User type.
 */

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

/**
 * Class UserType.
 */
class UserType extends AbstractType
{
    /**
     * Security helper.
     */
    private Security $security;

    /**
     * Constructor.
     *
     * @param Security $security Security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
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
            'name',
            TextType::class,
            [
                'label' => 'label.user.name',
                'required' => false,
                'attr' => ['max_length' => 64],
            ]
        );
        $builder->add(
            'surname',
            TextType::class,
            [
                'label' => 'label.user.surname',
                'required' => false,
                'attr' => ['max_length' => 64],
            ]
        );
        $builder->add(
            'email',
            TextType::class,
            [
                'label' => 'label.user.email',
                'required' => true,
                'attr' => ['max_length' => 255],
            ]
        );
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $builder->add(
                'admin',
                CheckboxType::class,
                [
                    'mapped' => false,
                    'label' => 'label.user.is_admin',
                    'required' => false,
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
        $resolver->setDefaults(['data_class' => User::class]);
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
        return 'user';
    }
}
