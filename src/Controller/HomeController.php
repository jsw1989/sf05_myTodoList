<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TaskFormType;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_page")
     */
    public function index(TaskRepository $taskRepository)
    {
        $taskTable = $taskRepository->findAllAvailable(new \DateTime());
        return $this->render('index.html.twig', [
            'task_table' => $taskTable,
            'standardStatus' => [
                'todo' => 'To do',
                'inprogress' => 'In progress',
                'done' => 'Done',
            ]
        ]);
    }

    /**
     * @Route("/create", name="create_task")
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $taskForm = $this->createForm(TaskFormType::class);
        $taskForm->handleRequest($request);

        if ($taskForm->isSubmitted() && $taskForm->isValid()) {

            $task = $taskForm->getData();
            $task->setCreatedAt(new \DateTime());
            $task->setUpdatedOn(new \DateTime());
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'Task saved.');
            return $this->redirectToRoute('home_page');
        }
        return $this->render('task/edit_task.html.twig', [
            'edit_form' => $taskForm->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit_task")
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, TaskRepository $taskRepository, $id)
    {
        $task = $taskRepository->find($id);
        $editForm = $this->createForm(TaskFormType::class, $task);
        $editForm->HandleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $task = $editForm->getData();
            $task->setUpdatedOn(new \DateTime());
            if ($task->getStatus() == "done") {
                $task->setCompletedAt(new \DateTime());
            } else {
                $task->setCompletedAt(null);
            }
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'Task updated.');
            return $this->redirectToRoute('home_page');
        }

        return $this->render('task/edit_task.html.twig', [
            'edit_form' => $editForm->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_task")
     */
    public function delete(EntityManagerInterface $entityManager, TaskRepository $taskRepository, $id)
    {
        $task = $entityManager->getRepository(Task::class)->find($id);
        $entityManager->remove($task);
        $entityManager->flush();
        $this->addFlash('success', 'Task removed.');
        return $this->redirectToRoute('home_page');
    }

    /**
     * @Route("/done/{id}", name="done_task")
     */
    public function doneTask(Request $request, EntityManagerInterface $entityManager, TaskRepository $taskRepository, $id)
    {
        $task = $taskRepository->find($id);
        $task->setStatus('done');
        $task->setCompletedAt(new \DateTime());
        $entityManager->persist($task);
        $entityManager->flush();

        $this->addFlash('success', 'Task marked as done.');
        return $this->redirectToRoute('home_page');

    }

    /**
     * @Route("/overdue_page", name="overdue_page")
     */
    public function overdueTasks(TaskRepository $taskRepository)
    {

        $taskTable = $taskRepository->findOverDue(new \DateTime());
        return $this->render('index.html.twig', [
            'task_table' => $taskTable,
            'standardStatus' => [
                'todo' => 'To do',
                'inprogress' => 'In progress',
                'done' => 'Done',
            ],
            "taskType" => "Overdue"
        ]);
    }
    /**
     * @Route("/due_page", name="due_page")
     */
    public function dueTasks(TaskRepository $taskRepository)
    {
        $taskTable = $taskRepository->findBy(['status'=>'done'], array('dueTo' => 'asc'));
        return $this->render('index.html.twig', [
            'task_table' => $taskTable,
            'standardStatus' => [
                'todo' => 'To do',
                'inprogress' => 'In progress',
                'done' => 'Done',
            ],
            "taskType" => "Due"
        ]);
    }


}


