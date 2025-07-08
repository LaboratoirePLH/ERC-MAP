<?php

namespace App\Controller\Admin;

use App\Entity\Chercheur;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Contracts\Translation\TranslatorInterface;

class NewAccountsCrudController extends AbstractCrudController
{
    private $em;
    private $translator;
    private $mailer;
    private $fromEmail;
    private $fromName;
    private $adminUrlGenerator;

    public function __construct(
        EntityManagerInterface $em,
        TranslatorInterface $translator,
        \Swift_Mailer $mailer,
        string $fromEmail,
        string $fromName,
        AdminUrlGenerator $adminUrlGenerator
    ) {
        $this->em = $em;
        $this->translator = $translator;
        $this->mailer = $mailer;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Chercheur::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['prenomNom', 'username', 'institution', 'mail', 'preferenceLangue', 'role', 'resetToken', 'id'])
            ->setPaginatorPageSize(30);
    }

    public function configureActions(Actions $actions): Actions
    {
        $activateUser = Action::new('activateUser', 'Activer le compte', 'fa fa-check')
            ->linkToCrudAction('activateUserAction');
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->add(Crud::PAGE_INDEX, $activateUser);
    }

    public function configureFields(string $pageName): iterable
    {
        $prenomNom = TextField::new('prenomNom');
        $username = TextField::new('username');
        $institution = TextField::new('institution');
        $mail = EmailField::new('mail');
        $password = TextField::new('password');
        $preferenceLangue = ChoiceField::new('preferenceLangue')->setChoices([
            'fr' => 'fr',
            'en' => 'en',
        ]);
        $dateAjout = DateTimeField::new('dateAjout');
        $role = TextField::new('role');
        $actif = Field::new('actif');
        $gestionnaireComptes = Field::new('gestionnaireComptes');
        $projets = AssociationField::new('projets');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $prenomNom, $username, $mail, $institution, $dateAjout];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$prenomNom, $username, $institution, $mail, $password, $preferenceLangue, $dateAjout, $role, $actif, $gestionnaireComptes, $id, $projets];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$prenomNom, $username, $institution, $mail, $password, $preferenceLangue, $dateAjout, $role, $actif, $gestionnaireComptes, $projets];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$prenomNom, $username, $mail, $institution, $role, $projets, $actif, $gestionnaireComptes];
        }
        return [];
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        return $this->container->get(EntityRepository::class)
            ->createQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->where('entity.actif = false');
    }

    public function activateUserAction(AdminContext $context)
    {
        /** @var Chercheur $chercheur */
        $chercheur = $context->getEntity()->getInstance();

        $user = $this->em->getRepository(Chercheur::class)->activate($chercheur->getId());
        $this->em->flush();

        // Send email notification that account was activated by administrators
        $mail = (new \Swift_Message($this->translator->trans('mails.account_activated.title')))
            ->setFrom([$this->fromEmail => $this->fromName])
            ->setTo($user->getMail())
            ->setReplyTo($this->fromEmail)
            ->setBody(
                $this->renderView(
                    'email/account_activated.html.twig',
                    compact('user')
                ),
                'text/html'
            );

        $this->mailer->send($mail);

        return $this->redirect($this->adminUrlGenerator->setController(NewAccountsCrudController::class)->generateUrl());
    }
}
