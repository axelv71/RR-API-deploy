<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Ressource;
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

        $users = [];
        for ($i = 0; $i < 10; $i++) {
            //Create a setting
            $setting = new Settings();
            $setting->setIsDark(false);


            //Create a user
            $user = new User();
            $user->setName($this->faker->name());
            $user->setSurname($this->faker->lastName());
            $user->setEmail($this->faker->email());
            $user->setRoles(["USER_CONNECTED"]);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, "password" . $i));
            $user->setPseudo($user->getPseudo().$user->getSurname());
            $user->setBirthday(new \DateTimeImmutable());
            $user->setIsActive(true);
            $user->setSettings($setting);
            $manager->persist($user);
            $users[] = $user;

            //Affect setting to user
            $setting->setUser($user);
            $manager->persist($setting);
        }

        $categories = [];
        for ($c = 0; $c < 10; $c++) {
            $category = new Category();
            $category->setTitle($this->faker->word());

            $manager->persist($category);
            $categories[] = $category;
        }

        $ressourcies = [];
        for ($r = 0; $r < 25; $r++) {
            $ressource = new Ressource();
            $ressource->setDescription($this->faker->paragraph())
                ->setIsValid((bool)mt_rand(0, 1))
                ->setIsPublished((bool)mt_rand(0, 1))
                ->setCategory($categories[mt_rand(0, count($categories) - 1)])
                ->setCreator($users[mt_rand(0, count($users) - 1)]);

            $ressourcies[] = $ressource;

            for ($c = 0; $c < mt_rand(0, 10); $c++) {
                $comment = new Comment();
                $comment->setContent($this->faker->paragraph())
                    ->setCreator($users[mt_rand(0, count($users) - 1)])
                    ->setRessource($ressource);

                $manager->persist($comment);
            }

            $manager->persist($ressource);
        }


        $manager->flush();
    }
}
