<?php

namespace App\Controller\User;

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

class DeleteUserController extends AbstractController
{

    #[Route('/user/{id}', name: 'delete_user_by_identifiant', methods:['DELETE'])]
    public function deleteUserById($userId, EntityManagerInterface $entityManager): JsonResponse | null
    {
        $player = $entityManager->getRepository(User::class)->findBy(['id'=>$userId]);
        if(count($player) !== 1) 
            return new JsonResponse('Wrong id', 404);
           {
            try{
                $entityManager->remove($player[0]);
                $entityManager->flush();

                $userStillExist = $entityManager->getRepository(User::class)->findBy(['id'=>$userId]);
    
                if(!empty($userStillExist)){
                    return throw new \Exception("User has not been deleted");
                }
                return new JsonResponse('User has been deleted', 204);
            }catch(\Exception $e){
                return new JsonResponse($e->getMessage(), 500);
            }
        } 
    }
}
