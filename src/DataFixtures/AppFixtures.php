<?php

namespace App\DataFixtures;
use App\Entity\Produits;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{

        public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');



        for ($i = 0; $i < 10; $i++) {
            $produit = new Produits();
            $produit->setNomProduit($faker->word())
                ->setnbPts(mt_rand(200, 2000))
                ->setQuantite(mt_rand(10, 40))

                ->setImage($faker->imageUrl(400, 400));

            $manager->persist($produit);
        }

        $manager->flush();
    }


}
