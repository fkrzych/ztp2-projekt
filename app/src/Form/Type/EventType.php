<?php
/**
 * Event type.
 */

namespace App\Form\Type;

use App\Entity\Event;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\DataTransformer\TagsDataTransformer;

/**
 * Class EventType.
 */
class EventType extends AbstractType
{
    /**
     * Tags data transformer.
     */
    private TagsDataTransformer $tagsDataTransformer;


    /**
     * Constructor.
     */
    public function __construct(TagsDataTransformer $tagsDataTransformer)
    {
        $this->tagsDataTransformer = $tagsDataTransformer;
    }

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder FormBuilderInterface
     * @param array<string, mixed> $options array<string, mixed>
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => 'label.event_name',
                'required' => true,
                'attr' => ['max_length' => 64],
            ]
        )
            ->add(
                'date',
                DateTimeType::class,
                [
                    'label' => 'label.date',
                    'required' => true,
                    'attr' => ['max_length' => 20],
                ]
            )
            ->add(
                'category',
                EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => fn ($category): string => $category->getName(),
                    'label' => 'label.category',
                    'placeholder' => 'label.none',
                    'required' => true,
                    'expanded' => true,
                    'multiple' => false,
                ]
            );


        $builder->add(
            'tags',
            TextType::class,
            [
                'label' => 'label.tags',
                'required' => false,
                'attr' => ['max_length' => 128],
            ]
        );

        $builder->get('tags')->addModelTransformer(
            $this->tagsDataTransformer
        );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver Options Resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Event::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string Block Prefix
     */
    public function getBlockPrefix(): string
    {
        return 'event';
    }
}
