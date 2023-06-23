<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Tag::class, 50, function (Tag $tag) {
            $tag->setName($this->faker->realText(15));

            if ($this->faker->boolean(50)) {
                $tag->setDeletedAt($this->faker->dateTimeThisMonth);
            }
        });

        $manager->flush();
    }
}
