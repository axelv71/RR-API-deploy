<?php

namespace App\Controller\EntityController;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    #[OA\Tag(name: "Category")]
    #[OA\Response(
        response: 200,
        description: "Returns all categories",
        content: new Model(type: Category::class, groups: ["getCategories"])
    )]
    #[Route('/api/categories', name: 'categories', methods: ['GET'])]
    public function getAllCategories(CategoryRepository $categoryRepository,): JsonResponse
    {
        //Return all categories in JSON format
        $categories = $categoryRepository->findAll();
        return $this->json($categories, 200, [], ['groups' => 'getCategories']);
    }

    #[OA\Tag(name: "Category")]
    #[OA\Parameter(name: "id", description: "Id of the category", in: "path", required: true, example: 1)]
    #[OA\Response(
        response: 200,
        description: "Returns one category",
        content: new Model(type: Category::class, groups: ["getCategories"])
    )]
    #[Route('/api/categories/{id}', name: 'oneCategory', methods: ['GET'])]
    public function getOneCategory(CategoryRepository $categoryRepository, $id): JsonResponse
    {
        //Return one category in JSON format
        $category = $categoryRepository->find($id);
        return $this->json($category, 200, [], ['groups' => 'getCategories']);
    }

    #[OA\Tag(name: "Category")]
    #[OA\RequestBody(content: new Model(type: Category::class, groups: ["createCategory"]))]
    #[OA\Response(
        response: 201,
        description: "Returns the created category",
        content: new Model(type: Category::class, groups: ["getCategories"])
    )]
    #[Route('/api/categories', name: 'createCategory', methods: ['POST'])]
    public function createCategory(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        //Create a new category
        $data = json_decode($request->getContent(), true);
        $category = new Category();
        $category->setTitle($data['title']);
        $entityManager->persist($category);
        $entityManager->flush();
        return $this->json($category, 201, [], ['groups' => 'getCategories']);
    }

    #[OA\Tag(name: "Category")]
    #[OA\Parameter(name: "id", description: "Id of the category", in: "path", required: true, example: 1)]
    #[OA\Response (
        response: 204,
        description: "Returns nothing",
        content: new Model(type: Category::class, groups: ["default"])
    )]
    #[Route('/api/categories/{id}', name: 'deleteCategory', methods: ['DELETE'])]
    public function deleteCategory(CategoryRepository $categoryRepository, $id, EntityManagerInterface $entityManager): JsonResponse
    {
        //Delete a category
        $category = $categoryRepository->find($id);
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->json($category, 204, [], ['groups' => 'getCategories']);
    }
}
