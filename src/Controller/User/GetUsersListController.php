<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserService;


class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/users', name: 'user_list', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $user = $this->userService->getUserList();
        return $this->json(
            $user,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }
}
