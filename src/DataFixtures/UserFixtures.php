<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const ADMIN = 'ADMIN_USER';

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ) {
    
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@admin.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setUsername('admin')
            ->setVerified(true)
            ->setPassword($this->hasher->hashPassword($user, 'admin'))
            ->setApiToken('admin_token');
        $this->addReference(self::ADMIN, $user);
        $manager->persist($user);

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail("user{$i}@user.com")
                ->setRoles(['ROLE_USER'])
                ->setUsername("user{$i}")
                ->setVerified(true)
                ->setPassword($this->hasher->hashPassword($user, '0000'))
                ->setApiToken("user{$i}_token");
            $this->addReference('USER'.$i, $user);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
