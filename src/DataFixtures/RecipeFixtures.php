<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Recipe;
use DateTimeImmutable;
use App\Entity\Category;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use FakerRestaurant\Provider\fr_FR\Restaurant;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly SluggerInterface $slugger) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Restaurant($faker));

        $categories = ['entree', 'plat', 'dessert', 'boisson'];
        foreach ($categories as $category) {
            $cat = new Category();
            $cat->setName($category)
                ->setSlug($this->slugger->slug($category))
                ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setUpdatedAt(DateTimeImmutable::createFromMutable($faker->dateTime()));
            $this->addReference($category, $cat);
            $manager->persist($cat);
        }

        for ($i = 0; $i < 10; $i++) {
            $recipe = new Recipe();
            $title = $faker->foodName();
            $recipe->setTitle($title)  
                ->setSlug($this->slugger->slug($title))          
                ->setUpdatedAt(DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setCategory($this->getReference($faker->randomElement($categories)))
                ->setContent($faker->paragraphs(10,true))
                ->setDuration($faker->numberBetween(5, 60))
                ->setUser($this->getReference('USER' . $faker->numberBetween(0, 9)));
            $manager->persist($recipe);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
