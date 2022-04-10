<?php
namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class ReservationFormType extends AbstractType
{
    #private $requestStack;

    #public function __construct(RequestStack $requestStack)
    #{
    #    $this->requestStack = $requestStack;
    #}

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

      $movieId = $options["movie_id"]; #$this->requestStack->getCurrentRequest()->get("id");
      $auditoriumId = $options["auditorium_id"];


      $builder->add('screening_id', null, [
                    'query_builder' => function (EntityRepository $er) use ($movieId) {
                       return $er->createQueryBuilder('screening')->where('screening.movie_id =:movie_id')
                                                                  ->setParameter('movie_id', $movieId);
                   },
               ]);

       $builder->add('contact');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'movie_id' => null,
            'auditorium_id' => null
        ]);
    }
}
