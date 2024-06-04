<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use PDO;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;


class UserController extends AbstractController
{
    #[Route('/users', name: 'liste_des_users', methods:['GET'])]
    public function getUsersList(EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $entityManager->getRepository(User::class)->findAll();
        return $this->json(
            $data,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }

    #[Route('/users', name: 'user_post', methods:['POST'])]
    public function createUser(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createFormBuilder()
            ->add('nom', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 1, 'max' => 255])
                ]
            ])
            ->add('age', NumberType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->getForm();

        $form->submit($data);

        if (!$form->isValid()) {
            return new JsonResponse('Invalid form', 400);
        }

        if ($data['age'] <= 21) {
            return new JsonResponse('Wrong age', 400);
        }

        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['name' => $data['nom']]);
        if ($existingUser) {
            return new JsonResponse('Name already exists', 400);
        }

        $user = new User();
        $user->setName($data['nom']);
        $user->setAge($data['age']);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json($user, 201, ['Content-Type' => 'application/json;charset=UTF-8']);
    }

    #[Route('/user/{id}', name: 'get_user_by_id', methods:['GET'])]
    public function getUserWithIdentifiant($id, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!ctype_digit($id)) {
            return new JsonResponse('Wrong id', 404);
        }

        $user = $entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            return new JsonResponse('Wrong id', 404);
        }

        return new JsonResponse(['name' => $user->getName(), 'age' => $user->getAge(), 'id' => $user->getId()], 200);
    }

 
}
