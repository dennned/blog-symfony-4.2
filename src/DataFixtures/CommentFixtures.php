<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Page;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $pagesRepo = $manager->getRepository(Page::class);
        $pages = $pagesRepo->findAll();

        foreach ($pages as $page) {
            for ($i = 1; $i <= 3; $i++) {
                $comment = new Comment();
                $comment->setComment('New comment '.$i.' ==>> '.$page->getTitle());

                $page->addComment($comment);
                $comment->addPage($page);
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
            PageFixtures::class
        ];
    }
}
