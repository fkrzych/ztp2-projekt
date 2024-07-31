<?php
/**
 * Tag fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Tag;

/**
 * Class TagFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class TagFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $this->createMany(20, 'tags', function (int $i) {
            $tag = new Tag();
            $tag->setName($this->faker->unique()->word);
            //$tag->setCreatedAt($this->faker->dateTimeBetween('-100 days', '4 days'));
            //$tag->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '4 days'));

            return $tag;
        });

        $this->manager->flush();
    }
}
