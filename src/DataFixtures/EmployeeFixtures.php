<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Repository\JobRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;


class EmployeeFixtures extends Fixture implements DependentFixtureInterface
{
    public  function __construct(JobRepository $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $employee = new Employee();
            $employee->setFirstname($faker->firstName);
            $employee->setLastname($faker->lastname);
            $employee->setEmployementDate(new \DateTime());
            $employee->setJob($this->jobRepository->find(rand(1, 20)));

            $manager->persist($employee);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            JobFixtures::class,
        );
    }
}
