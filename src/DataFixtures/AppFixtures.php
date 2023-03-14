<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Favorite;
use App\Entity\Like;
use App\Entity\Media;
use App\Entity\Relation;
use App\Entity\RelationType;
use App\Entity\Ressource;
use App\Entity\Role;
use App\Entity\Settings;
use App\Entity\Theme;
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
        // Delete all file in the media folder
        $folder_path = "/var/www/public/uploads/media";
        $files = glob($folder_path.'/*');
        foreach($files as $file) {

            if(is_file($file))

                // Delete the given file
                unlink($file);
        }

        $themes_array = [[
            "label" => "Default",
            "name" => "default",
            "primary_color" => "#B1ACFF",
            "secondary_color" => "#536DFE"
        ],[
            "label" => "Classic Lollipop",
            "name" => "lollipop",
            "primary_color" => "#CDB4DB",
            "secondary_color" => "#FFC8DD"
        ],[
            "label" => "Endless Galaxy",
            "name" => "galaxy",
            "primary_color" => "#5A189A",
            "secondary_color" => "#9D4EDD"
        ],[
            "label" => "Juicy Raspberry",
            "name" => "raspberry",
            "primary_color" => "#C71F37",
            "secondary_color" => "#E01E37"
        ],[
            "label" => "Ocean View",
            "name" => "ocean",
            "primary_color" => "#2196F3",
            "secondary_color" => "#90CAF9"
        ],[
            "label" => "Pumpkin Fall",
            "name" => "fall",
            "primary_color" => "#FF8800",
            "secondary_color" => "#FFA200"
        ],[
            "label" => "Shiny Spring",
            "name" => "spring",
            "primary_color" => "#4F772D",
            "secondary_color" => "#90A955"
        ],[
            "label" => "Sunny Summer",
            "name" => "summer",
            "primary_color" => "#FEC89A",
            "secondary_color" => "#F9DCC4"
        ],[
            "label" => "Wet Winter",
            "name" => "winter",
            "primary_color" => "#A2D2FF",
            "secondary_color" => "#BDE0FE"
        ]];

        $themes=[];
        foreach ($themes_array as $theme) {
            $theme = new Theme($theme["label"], $theme["name"], $theme["primary_color"], $theme["secondary_color"]);
            $themes[] = $theme;
            $manager->persist($theme);
        }




        $relation_name = [
            "Public",
            "Soi",
            "Conjoints",
            "Famille",
            "Enfants",
            "Parents",
            "Frères et soeurs",
            "Collègues",
            "Collaborateurs",
            "Managers",
            "Amis",
            "Inconnus"
        ];
        $relationTypes = [];
        for ($r = 0; $r < 5; $r++) {
            $relationType = new RelationType();
            $relationType->setName($relation_name[$r]);

            $relationTypes[] = $relationType;
            $manager->persist($relationType);
        }

        $users = [];
        for ($i = 0; $i < 10; $i++) {
            //Create a setting
            $setting = new Settings(isDark: false,
                allowNotifications: false,
                useDeviceMode: false,
                language: "fr",
                theme: $themes[mt_rand(0, count($themes) - 1)]);

            //Create a user
            $user = new User();
            $user->setFirstName($this->faker->name());
            $user->setLastName($this->faker->lastName());
            $user->setEmail($this->faker->email());
            $user->setRoles(["ROLE_USER", "ROLE_USER_AUTHENTICATED"]);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, "password" . $i));
            $user->setAccountName($user->getAccountName().$user->getLastName());
            $user->setBirthday(new \DateTimeImmutable());
            $user->setIsActive(true);
            $user->setIsVerified(true);
            $user->setSettings($setting);
            $manager->persist($user);
            $users[] = $user;

            //Affect setting to user
            $setting->setUser($user);
            $manager->persist($setting);
        }

        $setting = new Settings(isDark: false,
            allowNotifications: false,
            useDeviceMode: false,
            language: "fr",
            theme: $themes[mt_rand(0, count($themes) - 1)]);

        $user = new User();
        $user->setFirstName("test");
        $user->setLastName("test");
        $user->setEmail("test@gmail.com");
        $user->setRoles(["ROLE_USER", "ROLE_USER_AUTHENTICATED"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "test"));
        $user->setAccountName($user->getAccountName().$user->getLastName());
        $user->setBirthday(new \DateTimeImmutable());
        $user->setIsActive(true);
        $user->setIsVerified(true);
        $user->setSettings($setting);
        $manager->persist($user);
        $users[] = $user;


        // Relation
        $relations = [];
        for ($i = 0; $i < 30; $i++) {
            $sender = $users[mt_rand(0, count($users) - 1)];
            $receiver = $users[mt_rand(0, count($users) - 1)];
            //dd($receiver);

            while ($sender === $receiver) {
                $receiver = $users[mt_rand(0, count($users) - 1)];
            }

            $relation = new Relation($sender, $receiver, $relationTypes[mt_rand(0, count($relationTypes) - 1)]);
            $relation->setIsAccepted(mt_rand(0, 1));

            $relations[] = $relation;
            $manager->persist($relation);
        }

        // Communication
        // Cultures
        // Développement personnel
        // Intelligence émotionnelle
        // Loisirs
        // Monde professionnel
        // Parentalité
        // Qualité de vie
        // Recherche de sens
        // Santé physique
        // Santé psychique
        // Spiritualité
        // Vie affective
        $categories_array = [
            "Communication",
            "Cultures",
            "Développement personnel",
            "Intelligence émotionnelle",
            "Loisirs",
            "Monde professionnel",
            "Parentalité",
            "Qualité de vie",
            "Recherche de sens",
            "Santé physique",
            "Santé psychique",
            "Spiritualité",
            "Vie affective"
        ];

        $categories = [];
        foreach ($categories_array as $category) {
            $category = new Category($category);
            $categories[] = $category;
            $manager->persist($category);
        }

        $ressources = [];
        for ($r = 0; $r < 25; $r++) {
            $ressource = new Ressource();
            $ressource->setDescription($this->faker->paragraph())
                ->setIsValid((bool)mt_rand(0, 1))
                ->setIsPublished((bool)mt_rand(0, 1))
                ->setCategory($categories[mt_rand(0, count($categories) - 1)])
                ->setCreator($users[mt_rand(0, count($users) - 1)])
                ->addRelationType($relationTypes[mt_rand(0, count($relationTypes) - 1)]);


            // Comments
            for ($c = 0; $c < mt_rand(0, 10); $c++) {
                $comment = new Comment();
                $comment->setContent($this->faker->paragraph())
                    ->setCreator($users[mt_rand(0, count($users) - 1)])
                    ->setRessource($ressource);

                $manager->persist($comment);
            }

            $mime_type = [
                "image/jpeg",
                "image/png",
                "image/gif",
                "image/svg+xml",
                "pdf",
                "application/pdf",
                "mp4",
                "video/mp4",

                "video/quicktime"];
            // Media
            for ($m = 0; $m < mt_rand(0, 3); $m++)
            {
                $media = new Media();
                $media->setTitle($this->faker->word())
                    ->setTitle($this->faker->word())
                    ->setMimetype($mime_type[mt_rand(0, count($mime_type) - 1)])
                    ->setFilePath($this->faker->file('/var/www/github','/var/www/public/uploads/media' , false))
                    ->setRessource($ressource);

                $manager->persist($media);
            }

            // Like
            for ($u = 0; $u < count($users) - 1; $u++)
            {
                for ($r =0; $r < count($ressources) - 1; $r++) {
                    if ((bool)mt_rand(0,1)) {
                        $like = new Like();
                        $like->setUserLike($users[$u])
                            ->setRessourceLike($ressources[$r])
                            ->setIsLiked((bool)mt_rand(0, 1));

                        $favorite = new Favorite();
                        $favorite->setUserFavorite($users[$u])
                            ->setRessourceFavorite($ressources[$r]);

                        $manager->persist($favorite);
                        $manager->persist($like);
                    }
                }
            }



            $manager->persist($ressource);
            $ressources[] = $ressource;
        }
        $manager->flush();
    }
}
