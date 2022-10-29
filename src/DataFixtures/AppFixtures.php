<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Favorite;
use App\Entity\Like;
use App\Entity\Media;
use App\Entity\RelationType;
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


        $relationTypes = [];
        for ($r = 0; $r < 5; $r++) {
            $relationType = new RelationType();
            $relationType->setName($this->faker->word());

            $relationTypes[] = $relationType;
            $manager->persist($relationType);
        }

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



            // Comments
            for ($c = 0; $c < mt_rand(0, 10); $c++) {
                $comment = new Comment();
                $comment->setContent($this->faker->paragraph())
                    ->setCreator($users[mt_rand(0, count($users) - 1)])
                    ->setRessource($ressource);

                $manager->persist($comment);
            }

            // Media
            for ($m = 0; $m < mt_rand(0, 3); $m++)
            {
                $media = new Media();
                $media->setTitle($this->faker->word())
                    ->setLink($this->faker->imageUrl(640, 480, 'media', true))
                    ->setRessource($ressource);

                $manager->persist($media);
            }

            // Like
            for ($u = 0; $u < count($users) - 1; $u++)
            {
                for ($r =0; $r < count($ressourcies) - 1; $r++) {
                    if ((bool)mt_rand(0,1)) {
                        $like = new Like();
                        $like->setUserLike($users[$u])
                            ->setRessourceLike($ressourcies[$r])
                            ->setIsLiked((bool)mt_rand(0, 1));

                        $favorite = new Favorite();
                        $favorite->setUserFavorite($users[$u])
                            ->setRessourceFavorite($ressourcies[$r]);

                        $manager->persist($favorite);
                        $manager->persist($like);
                    }
                }
            }



            $manager->persist($ressource);
            $ressourcies[] = $ressource;
        }

        $manager->flush();
    }
}
