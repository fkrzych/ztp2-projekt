<?php
/**
 * Category fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;

/**
 * Class CategoryFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $this->createMany(20, 'categories', function (int $i) {
            $category = new Category();
            $category->setName($this->faker->unique()->word);
            $category->setCreatedAt($this->faker->dateTimeBetween('-100 days', '4 days'));
            $category->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '4 days'));

            return $category;
        });

        $this->manager->flush();
    }
}
