<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadProductData
 *
 */
class LoadProductData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 8; $i++) {
            $product = new Product();

            $product->setRef($this->genRef().$i);
            $product->setTitle('Produit '.$i);
            $product->setPrice(10.5 + $i);

            $manager->persist($product);
            $manager->flush();

            $this->addReference('product-'.strtolower($product->getTitle()), $product);
        }
    }

    public function genRef() {
        $number = rand(1, 1000);
        $t = time();
        $random = 'P' . $number . '' . $t;

        return $random;
    }
}
