<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Entity\User;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserList(): array
    {
        return $this->userRepository->findAll();
    }

    public function getUserById(int $id): User | null 
    {
        return $this->userRepository->find($id);
    }

    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }

    public function delete(User $user): void
    {
        $this->userRepository->delete($user);
    }
}