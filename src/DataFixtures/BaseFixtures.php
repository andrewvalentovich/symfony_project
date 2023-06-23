<?php

namespace App\DataFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Faker\Factory;

abstract class BaseFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    protected $manager;

    /**
     * @param Generator $faker
     */
    protected $faker;

    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        $this->manager = $manager;
        $this->loadData($manager);
        $manager->flush();
    }

    abstract function loadData(ObjectManager $manager);

    protected function createOne(string $className, callable $factory)
    {
        $entity = new $className;
        $factory($entity);

        $this->manager->persist($entity);

        return $entity;
    }

    protected function createMany(string $className, int $count = 0, callable $factory)
    {
        for ($i = 0; $i < $count; $i++) {
            $this->createOne($className, $factory);
        }
    }


}
