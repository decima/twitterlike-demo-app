<?php

namespace App\DataFixtures;

use App\Entity\Tweet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\ORM\Doctrine\Populator;
use Random\Randomizer;

class TweetFixtures extends Fixture implements DependentFixtureInterface
{
    private $counter = 0;

    public function load(ObjectManager $manager): void
    {

        $availableUsers = UserFixtures::data();
        $r = new Randomizer();
        $generator = \Faker\Factory::create();
        $populator = new Populator($generator, $manager);

        $populator->addEntity(
            Tweet::class,
            1000,
            [
                'author' => fn() => $this->getReference($r->pickArrayKeys($availableUsers, 1)[0]),
            ],
            [
                fn(Tweet $tweet) => rand(0, 100) % 20 !== 0 ?: $tweet->setParent(null),
            ]

        );

        $populator->execute();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
