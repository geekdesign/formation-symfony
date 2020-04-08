<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Booking;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser  ->setFirstName('Pierre-Alain')
                    ->setLastName('Schütz')
                    ->setEmail('schutz.pa@gmail.com')
                    ->setHash($this->encoder->encodePassword($adminUser, 'schutz12'))
                    ->setPicture('https://avatars.io/twitter/schuetzpa')
                    ->setIntroduction($faker->sentence())
                    ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                    ->addUserRole($adminRole);
        $manager->persist($adminUser);

        $users = [];
        $genres = ['male', 'female'];

        //Nous gérons les utilisateurs
        for ($i=1; $i < 10; $i++) { 
            $user = new User();

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $picture .= ($genre == "male" ? 'men/' : 'women/') . $pictureId;

            $contentUser = '<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>';

            $hash = $this->encoder->encodePassword($user, 'password');

            $user   ->setFirstName($faker->firstname($genre)) 
                    ->setLastName($faker->lastname)
                    ->setEmail($faker->email)
                    ->setIntroduction($faker->sentence())
                    ->setDescription($contentUser)
                    ->setHash($hash)
                    ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }

        //On va créer 30 Ad
        for ($i=1; $i < 30; $i++) { 
        //On crée un objet Ad
        $ad = new Ad();

        //On crée un titre d'une longueure de 20mots
        $title  = $faker->sentence();
        //On génère une image aléatoire
        $coveImage = $faker->imageUrl(1000, 350);
        //On crée une intorduction de 2 paragraphe
        $introduction = $faker->paragraph(2);
        //On crée un contenu de 5 paragraphes que l'on sépare par des p
        $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';
        //on créer les auteurs
        $user = $users[mt_rand(0, count($users) - 1)];

        //On ajoute les données dans l'objet Ad
        $ad ->setTitle($title)
            ->setCoverImage($coveImage)
            ->setIntroduction($introduction)
            ->setContent($content)
            ->setRooms(mt_rand(1, 5))
            ->setPrice(mt_rand(60, 200))
            ->setAuthor($user);

        //On ajoute entre 2 et 5 images par Ad 
        for ($j=1; $j <= mt_rand(2,5); $j++) { 
            $image  = new Image();
            $image  ->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);

            $manager->persist($image);
        }

        //Gestion des réservation
        for ($j=1; $j <= mt_rand(0, 10); $j++) { 
            $booking    = new Booking();
            $createdAt  = $faker->dateTimeBetween('-6 months');
            $startDate  = $faker->dateTimeBetween('-3 months');
            $duration   = mt_rand(3, 10);
            $endDate    = (clone $startDate)->modify("+$duration days");
            $amount     = $ad->getPrice() * $duration;
            $booker     = $users[mt_rand(0, count($users) - 1)];

            $booking    ->setBooker($booker)
                        ->setAd($ad)
                        ->setStartDate($startDate)
                        ->setEndDate($endDate)
                        ->setCreatedAt($createdAt)
                        ->setAmount($amount)
                        ->setComment($faker->paragraph(1));

            $manager->persist($booking);

            //Gestion des commentaires
            if (mt_rand(0,1)) {
                $comment = new Comment();
                $comment->setContent($faker->paragraph())
                        ->setRating(mt_rand(1,5))
                        ->setAuthor($booker)
                        ->setAd($ad);
                $manager->persist($comment);
            }

        }
        
        //On persiste dans la base de donnée l'objet
        $manager->persist($ad);

        }

        $manager->flush();
    }
}
