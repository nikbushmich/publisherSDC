<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\BookCategoryListResponse;
use App\Service\BookCategoryServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookCategoryController extends AbstractController
{
    public function __construct(private readonly BookCategoryServiceInterface $bookCategoryService)
    {
    }

    #[Route(path: '/api/v1/book/categories', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns book categories',
        attachables: [new Model(type: BookCategoryListResponse::class)]
    )]
    public function categories(): Response
    {
//         throw new \RuntimeException('test exceptions');
        return $this->json($this->bookCategoryService->getCategories());
    }
}
