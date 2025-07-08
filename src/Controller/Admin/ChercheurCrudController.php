<?php

namespace App\Controller\Admin;

use App\Entity\Chercheur;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ChercheurCrudController extends AbstractCrudController
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return Chercheur::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Chercheur')
            ->setEntityLabelInPlural('Chercheur')
            ->setSearchFields(['prenomNom', 'username', 'institution', 'mail', 'preferenceLangue', 'role', 'resetToken', 'id'])
            ->setPaginatorPageSize(30);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        return $this->container->get(EntityRepository::class)
            ->createQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->where('entity.actif = true');
    }

    public function configureFields(string $pageName): iterable
    {
        $prenomNom = TextField::new('prenomNom');
        $username = TextField::new('username');
        $mail = EmailField::new('mail');
        $newPassword = TextField::new('newPassword');
        $institution = TextField::new('institution');
        $role = ChoiceField::new('role')
            ->setChoices([
                'Administrateur' => 'admin',
                'Modérateur'     => 'moderator',
                'Contributeur'   => 'contributor',
                'Utilisateur'    => 'user',
            ]);
        $projets = AssociationField::new('projets');
        $actif = BooleanField::new('actif');
        $gestionnaireComptes = BooleanField::new('gestionnaireComptes');
        $dateAjout = DateField::new('dateAjout');
        $id = IntegerField::new('id', 'ID');
        $nomsProjets = TextField::new('nomsProjets');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $prenomNom, $username, $mail, $institution, $role, $nomsProjets];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $prenomNom, $username, $mail, $institution, $role, $dateAjout, $actif, $gestionnaireComptes, $projets];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$prenomNom, $username, $mail, $newPassword, $institution, $role, $projets, $actif, $gestionnaireComptes];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$id->setDisabled(true), $prenomNom, $username, $mail, $newPassword, $institution, $role, $projets, $actif, $gestionnaireComptes];
        }
        return [];
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!!$entityInstance->getNewPassword()) {
            $encodedPassword = $this->encodePassword($entityInstance, $entityInstance->getNewPassword());
            $entityInstance->setPassword($encodedPassword);
        }
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!!$entityInstance->getNewPassword()) {
            $encodedPassword = $this->encodePassword($entityInstance, $entityInstance->getNewPassword());
            $entityInstance->setPassword($encodedPassword);
        }
        parent::persistEntity($entityManager, $entityInstance);
    }

    private function encodePassword($user, $password)
    {
        return $this->passwordHasher->hashPassword($user, $password);
    }
}
