<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\Settings;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Generator as FakerGenerator;




class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $userPasswordHasher;
    private FakerGenerator $faker;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->faker = Factory::create("fr_FR");
    }
    public function load(ObjectManager $manager): void
    {
        $role = new Role();
        $role->setName("ROLE_USER_CONNECTED");
        $manager->persist($role);

        for ($i = 0; $i < 10; $i++) {
            //Create a setting
            $setting = new Settings();
            $setting->setIsDark(false);


            //Create a user
            $user = new User();
            $user->setName($this->faker->name());
            $user->setSurname($this->faker->lastName());
            $user->setEmail($this->faker->email());
            $user->setRoles(["ROLE_USER_CONNECTED"]);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, "password" . $i));
            $user->setPseudo($user->getPseudo().$user->getSurname());
            $user->setBirthday(new \DateTimeImmutable());
            $user->setSettings($setting);
            $manager->persist($user);

            //Affect setting to user
            $setting->setUser($user);
            $manager->persist($setting);
        }
        $manager->flush();
    }
}
