<?php
/**
 * SelectTealist type.
 */

namespace Form\Type;

use App\Entity\Tealist;
use App\Repository\TealistRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

/**
 * Class SelectTealistType.
 */
class SelectTealistType extends AbstractType
{
    /**
     * Constructor.
     *
     * @param Security $security security
     */
    public function __construct(
        private Security $security,
    ) {
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
        // grab the user, do a quick sanity check that one exists
        $user = $this->security->getUser();
        if (!$user) {
            throw new \LogicException('The FriendMessageFormType cannot be used without an authenticated user!');
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            if (null !== $event->getData()->getFriend()) {
                // we don't need to add the friend field because
                // the message will be addressed to a fixed friend
                return;
            }

            $form = $event->getForm();

            $formOptions = [
                'class' => User::class,
                'choice_label' => 'fullName',
                'query_builder' => function (TealistRepository $tealistRepository): void {
                    // call a method on your repository that returns the query builder
                    // return $userRepository->createFriendsQueryBuilder($user);
                },
            ];

            // create the field, this is similar the $builder->add()
            // field name, field type, field options
            $form->add('select', ChoiceType::class, $formOptions);
        });
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Tealist::class]);
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
