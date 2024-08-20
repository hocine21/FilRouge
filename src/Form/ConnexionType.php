<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ConnexionType extends AbstractType
{
    private $csrfTokenManager;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->csrfTokenManager = $csrfTokenManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('AdresseEmail', EmailType::class, [
                'label' => 'Adresse Email',
            ])
            ->add('MotDePasse', PasswordType::class, [
                'label' => 'Mot de Passe',
            ])
            ->add('_csrf_token', HiddenType::class, [
                'data' => $this->csrfTokenManager->getToken('authenticate')->getValue(),
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Se connecter'
            ]);
    }
}
    