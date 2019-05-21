<?php

namespace App\DataFixtures;

use App\Entity\Page;
use App\Entity\Term;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PageFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $repoTerm = $manager->getRepository(Term::class);
        for ($i = 1; $i <= 3; $i++) {
            $page = new Page();
            $page->setTitle('TITLE'.$i);
            $page->setBody('BODY'.$i);

            $term = $repoTerm->findOneByName('TERM'.$i);
            if ($term) {
                $page->setCategory($term);
            }

            $manager->persist($page);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            TermFixtures::class
        ];
    }
}
