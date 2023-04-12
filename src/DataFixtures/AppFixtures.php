<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Favorite;
use App\Entity\Language;
use App\Entity\Like;
use App\Entity\Media;
use App\Entity\Notification;
use App\Entity\NotificationType;
use App\Entity\Relation;
use App\Entity\RelationType;
use App\Entity\Ressource;
use App\Entity\RessourceType;
use App\Entity\Settings;
use App\Entity\Statistic;
use App\Entity\StatisticType;
use App\Entity\Theme;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator as FakerGenerator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;
    private FakerGenerator $faker;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // Delete all file in the media folder
        $folder_path = '/var/www/public/uploads/media';
        $files = glob($folder_path.'/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                // Delete the given file
                unlink($file);
            }
        }

        $themes_array = [[
            'label' => 'Default',
            'name' => 'default',
            'primary_color' => '#B1ACFF',
            'secondary_color' => '#536DFE',
        ], [
            'label' => 'Classic Lollipop',
            'name' => 'lollipop',
            'primary_color' => '#CDB4DB',
            'secondary_color' => '#FFC8DD',
        ], [
            'label' => 'Endless Galaxy',
            'name' => 'galaxy',
            'primary_color' => '#5A189A',
            'secondary_color' => '#9D4EDD',
        ], [
            'label' => 'Juicy Raspberry',
            'name' => 'raspberry',
            'primary_color' => '#C71F37',
            'secondary_color' => '#E01E37',
        ], [
            'label' => 'Ocean View',
            'name' => 'ocean',
            'primary_color' => '#2196F3',
            'secondary_color' => '#90CAF9',
        ], [
            'label' => 'Pumpkin Fall',
            'name' => 'fall',
            'primary_color' => '#FF8800',
            'secondary_color' => '#FFA200',
        ], [
            'label' => 'Shiny Spring',
            'name' => 'spring',
            'primary_color' => '#4F772D',
            'secondary_color' => '#90A955',
        ], [
            'label' => 'Sunny Summer',
            'name' => 'summer',
            'primary_color' => '#FEC89A',
            'secondary_color' => '#F9DCC4',
        ], [
            'label' => 'Wet Winter',
            'name' => 'winter',
            'primary_color' => '#A2D2FF',
            'secondary_color' => '#BDE0FE',
        ]];

        $themes = [];
        foreach ($themes_array as $theme) {
            $theme = Theme::create($theme['label'], $theme['name'], $theme['primary_color'], $theme['secondary_color']);
            $themes[] = $theme;
            $manager->persist($theme);
        }

        $languages_array = [
            ['Français', 'fr'],
            ['Anglais', 'en'],
        ];
        $languages = [];
        foreach ($languages_array as $language) {
            $language = Language::create($language[0], $language[1]);
            $languages[] = $language;
            $manager->persist($language);
        }

        $relation_names = [
            ['Public', 'public'],
            ['Soi', 'self'],
            ['Conjoint', 'spouse'],
            ['Famille', 'family'],
            ['Enfant', 'child'],
            ['Parent', 'parent'],
            ['Frère et soeur', 'sibling'],
            ['Collègue', 'colleague'],
            ['Collaborateur', 'collaborator'],
            ['Manager', 'manager'],
            ['Ami', 'friend'],
            ['Inconnu', 'unknown'],
        ];
        $relationTypes = [];
        foreach ($relation_names as $r => $relation_name) {
            $relationType = RelationType::create($relation_name[0], $relation_name[1]);
            $relationTypes[] = $relationType;
            $manager->persist($relationType);
        }

        $notification_types = [
            ['Like', 'like'],
            ['Comment', 'comment'],
            ['Favorite', 'favorite'],
            ['Relation', 'relation'],
            ['Ressource', 'ressource'],
        ];

        $notificationTypes = [];
        foreach ($notification_types as $notification_type) {
            $notificationType = NotificationType::create($notification_type[0], $notification_type[1]);
            $notificationTypes[] = $notificationType;
            $manager->persist($notificationType);
        }

        $users = [];
        for ($i = 0; $i < 10; ++$i) {
            // Create a setting
            $setting = Settings::create(isDark: false,
                allowNotifications: false,
                useDeviceMode: false,
                language: $languages[mt_rand(0, count($languages) - 1)],
                theme: $themes[mt_rand(0, count($themes) - 1)]);

            // Create a user
            $user = new User();
            $user->setFirstName($this->faker->name());
            $user->setLastName($this->faker->lastName());
            $user->setEmail($this->faker->email());
            $user->setRoles(['ROLE_USER', 'ROLE_USER_AUTHENTICATED']);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, 'password'));
            $user->setAccountName($user->getFirstName().$user->getLastName());
            $user->setBirthday(new \DateTimeImmutable());
            $user->setIsActive(true);
            $user->setIsVerified(true);
            $user->setSettings($setting);
            $manager->persist($user);
            $users[] = $user;

            // Affect setting to user
            $setting->setUser($user);
            $manager->persist($setting);
        }

        $setting = Settings::create(isDark: false,
            allowNotifications: false,
            useDeviceMode: false,
            language: $languages[mt_rand(0, count($languages) - 1)],
            theme: $themes[mt_rand(0, count($themes) - 1)]);

        $user = new User();
        $user->setFirstName('test');
        $user->setLastName('test');
        $user->setEmail('test@gmail.com');
        $user->setRoles(['ROLE_USER', 'ROLE_USER_AUTHENTICATED']);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, 'test'));
        $user->setAccountName($user->getAccountName().$user->getLastName());
        $user->setBirthday(new \DateTimeImmutable());
        $user->setIsActive(true);
        $user->setIsVerified(true);
        $user->setSettings($setting);
        $manager->persist($user);
        $users[] = $user;

        // Relation
        $relations = [];
        for ($i = 0; $i < 30; ++$i) {
            $sender = $users[mt_rand(0, count($users) - 1)];
            $receiver = $users[mt_rand(0, count($users) - 1)];
            // dd($receiver);

            while ($sender === $receiver) {
                $receiver = $users[mt_rand(0, count($users) - 1)];
            }

            $relation = Relation::create($sender, $receiver, $relationTypes[mt_rand(0, count($relationTypes) - 1)]);
            $relation->setIsAccepted(true);

            $relations[] = $relation;
            $manager->persist($relation);
        }

        $categories_array = [
            ['Toutes', 'all'],
            ['Communication', 'communication'],
            ['Cultures', 'cultures'],
            ['Développement personnel', 'personal_development'],
            ['Intelligence émotionnelle', 'emotional_intelligence'],
            ['Loisirs', 'hobbies'],
            ['Monde professionnel', 'professional_world'],
            ['Parentalité', 'parenthood'],
            ['Qualité de vie', 'quality_of_life'],
            ['Recherche de sens', 'search_for_meaning'],
            ['Santé physique', 'physical_health'],
            ['Santé psychique', 'mental_health'],
            ['Spiritualité', 'spirituality'],
            ['Vie affective', 'affective_life'],
        ];

        $resource_type_array = [
            'Activité / Jeu à réaliser',
            'Article',
            'Carte défi',
            'Cours au format PDF',
            'Exercice / Atelier',
            'Fiche de lecture',
            'Jeu en ligne',
            'Vidéo',
            'Image',
            'Texte',
            'Audio',
        ];

        $resource_types = [];
        foreach ($resource_type_array as $resource_type) {
            $resource_type = RessourceType::create($resource_type);
            $resource_types[] = $resource_type;
            $manager->persist($resource_type);
        }

        $categories = [];
        foreach ($categories_array as $category) {
            $category = Category::create($category[0], $category[1]);
            $categories[] = $category;
            $manager->persist($category);
        }

        $ressources = [];
        for ($r = 0; $r < 25; ++$r) {
            $ressource = new Ressource();
            $ressource->setDescription($this->faker->paragraph())
                ->setIsValid((bool) mt_rand(0, 1))
                ->setIsPublished((bool) mt_rand(0, 1))
                ->setCategory($categories[mt_rand(0, count($categories) - 1)])
                ->setCreator($users[mt_rand(0, count($users) - 1)])
                ->setTitle($this->faker->sentence(4, true))
                ->setType($resource_types[mt_rand(0, count($resource_types) - 1)])
                ->addRelationType($relationTypes[mt_rand(0, count($relationTypes) - 1)]);

            // Comments
            for ($c = 0; $c < mt_rand(0, 3); ++$c) {
                $comment = new Comment();
                $comment->setContent($this->faker->paragraph())
                    ->setCreator($users[mt_rand(0, count($users) - 1)])
                    ->setRessource($ressource)
                    ->setIsValid(true);

                $manager->persist($comment);
            }

            $mime_type = [
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/svg+xml',
                'pdf',
                'application/pdf',
                'mp4',
                'video/mp4',
                'video/quicktime'];
            /*// Media
            for ($m = 0; $m < mt_rand(0, 3); $m++)
            {
                $media = new Media();
                $media->setTitle($this->faker->word())
                    ->setTitle($this->faker->word())
                    ->setMimetype($mime_type[mt_rand(0, count($mime_type) - 1)])
                    ->setFilePath($this->faker->file('/var/www/github','/var/www/public/uploads/media' , false))
                    ->setRessource($ressource);

                $manager->persist($media);
            }*/

            $manager->persist($ressource);
            $ressources[] = $ressource;
        }
        // Like
        for ($u = 0; $u < count($users) - 1; ++$u) {
            for ($r = 0; $r < count($ressources) - 1; ++$r) {
                if ((bool) mt_rand(0, 1)) {
                    $like = new Like();
                    $like->setUserLike($users[$u])
                        ->setRessourceLike($ressources[$r])
                        ->setIsLiked(true);

                    $favorite = new Favorite();
                    $favorite->setUserFavorite($users[$u])
                        ->setRessourceFavorite($ressources[$r]);

                    $manager->persist($favorite);
                    $manager->persist($like);
                }
            }
        }

        // Notifications
        // Create notification for test user
        for ($i = 0; $i < 10; ++$i) {
            $type_of_notification = $notificationTypes[mt_rand(0, count($notificationTypes) - 1)];
            if ($type_of_notification == 1) {
                $content = "a aimé votre ressource";
            } elseif ($type_of_notification == 2){
                $content = "a commenté votre ressource";
            } elseif ($type_of_notification == 3) {
                $content = "a mis votre ressource en favoris";
            } elseif($type_of_notification == 4) {
                $content = "souhaite vous ajouter en tant que relation";
            } else {
                $content = "a ajouté une nouvelle ressource";
            }
            $notification = Notification::create($users[mt_rand(0, count($users) - 1)],
                $user,
                $type_of_notification, $content ,
                $ressources[mt_rand(0, count($ressources) - 1)]);

            if ('relation' == $notification->getNotificationType()->getName()) {
                $notification->setResource(null);
            }

            $manager->persist($notification);
        }

        //Statistiques
        $statisticsType_array = [
            ['Consultation', 'consultation'],
            ['Recherche', 'recherche'],
            ['Exploitation', 'exploitation'],
            ['Creation', 'creation']
        ];

        $statisticsType = [];
        foreach($statisticsType_array as $statisticsType){
            $statisticsType = StatisticType::create($statisticsType[0], $statisticsType[1]);
            $statisticsTypes[] = $statisticsType;
            $manager->persist($statisticsType);
        }

        for ($i=0; $i < 50; $i++) {
            $statistic = Statistic::create($statisticsTypes[mt_rand(0, count($statisticsTypes) - 1)],
                $relationTypes[mt_rand(0, count($relationTypes) - 1)],
                $resource_types[mt_rand(0, count($resource_types) - 1)],
                $categories[mt_rand(0, count($categories) - 1)]);

            $manager->persist($statistic);
        }

        $manager->flush();
    }
}
