<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends BaseFixture
{
    const SELLER_USER_ID = 121212121;
    const BUYER_USER_ID = 122323332;

    /**
     * @param ObjectManager $manager
     */
    protected function loadData(ObjectManager $manager)
    {
        $seller = (new User())
            ->setUsername('telegram'.self::SELLER_USER_ID)
            ->setTelegramUsername('seller')
            ->setTelegramId(self::SELLER_USER_ID)
            ->setFirstName($this->faker->firstName)
            ->setLastName($this->faker->lastName)
            ->setLocale('en-US')
            ->setIsTelegramBot(false)
            ->addTelegramChatId(self::SELLER_USER_ID)
        ;
        $manager->persist($seller);
        $this->addReference($this->getReferenceKey(User::class, 0), $seller);

        $buyer = (new User())
            ->setUsername('telegram'.self::BUYER_USER_ID)
            ->setTelegramUsername('buyer')
            ->setTelegramId(self::BUYER_USER_ID)
            ->setFirstName($this->faker->firstName)
            ->setLastName($this->faker->lastName)
            ->setLocale('en-US')
            ->setIsTelegramBot(false)
            ->addTelegramChatId(self::BUYER_USER_ID)
        ;
        $manager->persist($buyer);
        $this->addReference($this->getReferenceKey(User::class, 1), $buyer);

        $manager->flush();
    }
}
