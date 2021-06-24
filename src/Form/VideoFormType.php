<?php

namespace App\Form;

use App\Entity\Video;
use App\Service\VideoEmbedLinkExtractor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoFormType extends AbstractType
{
    private VideoEmbedLinkExtractor $extractor;

    public function __construct(VideoEmbedLinkExtractor $extractor)
    {
        $this->extractor = $extractor;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', TextareaType::class, [
                'setter' => function(Video $video, ?string $url): void {
                    $video->setUrl($this->extractor->rename($url));
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
