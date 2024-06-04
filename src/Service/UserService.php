<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Entity\User;
use Psr\Log\LoggerInterface;


class UserService
{
    private UserRepository $userRepository;
    private LoggerInterface $logger;        

    public function __construct(UserRepository $userRepository, LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->logger = $logger;

    }

    public function getUserList(): array
    {
        try {
            return $this->userRepository->findAll();
        } catch (\Exception $e) {
            $this->logger->error('Failed to fetch user list', ['exception' => $e]);
            throw new \RuntimeException('Unable to fetch user list');
        }
    }

    public function getUserById(int $id): User | null 
    {
        return $this->userRepository->find($id);
    }

    public function save(User $user): void
    {
        try {
            $this->userRepository->save($user);
        } catch (\Exception $e) {
            $this->logger->error('Failed to save user', ['exception' => $e]);
            throw new \RuntimeException('Unable to save user');
        }
    }

    public function delete(User $user): void
    {

        try {
                $this->userRepository->delete($user);
        } catch (\Exception $e) {
                $this->logger->error('Failed to delete user', ['exception' => $e]);
                throw new \RuntimeException('Unable to delete user');
        }
    }
}