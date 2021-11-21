<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Task;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $this->manager = $manager;
        $this->faker = Factory::create('en_US');

        // Creation de 5 fixtures
        for ($i = 0; $i < 5; $i++) {
            $status = $this->faker->randomElement([
                'todo', 'inprogress', 'done'
            ]);
            $task = (new Task())
                ->setTitle($this->faker->sentence(2))
                ->setStatus($status)
                ->setNote($this->faker->paragraph(2))
                ->setCreatedAt(new \DateTime())
                ->setUpdatedOn(new \DateTime())
                ->setDueTo(new \DateTime("+$i days"));
            if ($status == "done") {
                $task->setCompletedAt(new \DateTime());
            }

            $manager->persist($task);
        }

        $manager->flush();
    }
}
