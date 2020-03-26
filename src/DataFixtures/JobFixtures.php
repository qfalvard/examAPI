<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class JobFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $job = new Job();
        $job->setTitle('remouleur');
        $manager->persist($job);

        $job2 = new Job();
        $job2->setTitle('lavandiere');
        $manager->persist($job2);

        $job3 = new Job();
        $job3->setTitle('forgeron');
        $manager->persist($job3);

        $job4 = new Job();
        $job4->setTitle('bazanier');
        $manager->persist($job4);

        $job5 = new Job();
        $job5->setTitle('ferblantier');
        $manager->persist($job5);

        $manager->flush();
    }
}
