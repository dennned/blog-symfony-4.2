<?php

namespace App\DataFixtures;

use App\Entity\Term;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TermFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 3; $i++) {
            $term = new Term();
            $term->setName('TERM'.$i);
            $term->setDescription('DESCRIPTION'.$i);

            $manager->persist($term);
        }
        $manager->flush();
    }

}