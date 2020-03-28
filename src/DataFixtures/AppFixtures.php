<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-CH');


        //On va créer 30 Ad
        for ($i=1; $i < 20; $i++) { 
        //On crée un objet Ad
        $ad = new Ad();

        //On crée un titre d'une longueure de 20mots
        $title = $faker->sentence();
        //On génère une image aléatoire
        $coveImage = $faker->imageUrl(1000, 350);
        //On crée une intorduction de 2 paragraphe
        $introduction = $faker->paragraph(2);
        //On crée un contenu de 5 paragraphes que l'on sépare par des p
        $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';


        //On ajoute les données dans l'objet Ad
        $ad ->setTitle($title)
            ->setCoverImage($coveImage)
            ->setIntroduction($introduction)
            ->setContent($content)
            ->setRooms(mt_rand(1, 5))
            ->setPrice(mt_rand(60, 200));

        //On ajoute entre 2 et 5 images par Ad 
        for ($j=1; $j <= mt_rand(2,5); $j++) { 
            $image = new Image();
            $image  ->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);

            $manager->persist($image);
        }
        
        //On persiste dans la base de donnée l'objet
        $manager->persist($ad);

        }

        $manager->flush();
    }
}
