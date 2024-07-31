<?php
/**
 * Event fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Tag;
use App\Entity\Event;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class EventFixtures.
 */
class EventFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (!$this->manager instanceof \Doctrine\Persistence\ObjectManager || !$this->faker instanceof \Faker\Generator) {
            return;
        }

        $this->createMany(100, 'events', function (int $i) {
            $event = new Event();
            $event->setName($this->faker->sentence);
            $event->setDate($this->faker->dateTimeBetween('-100 days', '4 days'));
            /** @var Category $category */
            $category = $this->getRandomReference('categories');
            $event->setCategory($category);

            /* @var Tag $tag */
            for ($i = 0; $i < 5; ++$i) {
                $tag = $this->getRandomReference('tags');
                $event->addTag($tag);
            }

            /** @var User $author */
            $author = $this->getRandomReference('admins');
            $event->setAuthor($author);

            return $event;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: CategoryFixtures::class, 1: TagFixtures::class, 2: UserFixtures::class}
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class, TagFixtures::class, UserFixtures::class];
    }
}
