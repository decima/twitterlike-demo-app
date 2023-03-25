<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Service\Attribute\Required;

class UserFixtures extends Fixture
{

    const USER_1 = 'USER_1';
    const USER_2 = 'USER_2';
    const USER_3 = 'USER_3';

    #[Required]
    public UserPasswordHasherInterface $userPasswordHasher;

    public function load(ObjectManager $manager): void
    {
        foreach (self::data() as $k => $d) {
            $user = new User();
            $user->setUsername($d['username']);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $d['password']));
            $manager->persist($user);
            $this->addReference($k, $user);
        }
        $manager->flush();
    }

    public static function data(): array
    {
        return [
            self::USER_1 => ['username' => 'decima', 'password' => 123456],
            self::USER_2 => ['username' => 'user2', 'password' => 123456],
            self::USER_3 => ['username' => 'user3', 'password' => 123456],
        ];
    }
}
