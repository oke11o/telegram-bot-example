<?php

namespace App\DataFixtures;

use App\Entity\Participant;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ParticipantFixtures extends BaseFixture implements DependentFixtureInterface
{
    private static $users = [];

    protected function loadData(ObjectManager $manager)
    {
        $this::$users = $this->getRandomReferences(User::class, 2);

        $this->createMany(
            Participant::class,
            10,
            function (Participant $participant, $count) use ($manager) {

                $participant
                    ->setYandexWallet($this->getString())
                    ->setEthWallet($this->getString())
                    ->setAmount((string)$this->faker->randomFloat(10))
                    ->setUser($this->getRandomReference(User::class));
            }
        );

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [UserFixtures::class];
    }

    /**
     * @return string
     */
    function getString(): string
    {
        return strtolower(substr(str_replace([' ', '.', ','], '', $this->faker->text(200)), 0, 32));
    }
}
