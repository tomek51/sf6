<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends AbstractController
{
    #[Route('/{slug}', name: 'product_category')]
    public function index($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$category) {
            throw new NotFoundHttpException("la catégorie n'existe pas");
        }

        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category
        ]);
    }

    /**
     * @Route("/{category_slug}/{slug}", name = "product_show")
     */

    public function show($slug, ProductRepository $productRepository)
    {
        $product = $productRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$product) {
            throw $this->createNotFoundException(" le produit n'existe pas ");
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * 
     * @Route("/admin/product/create", name ="product_create")
     */
    public function create(FormFactoryInterface $factory) {

        $builder= $factory->createBuilder();

        $builder ->add('name', TextType::class,[
            'label' => 'Nom du produit',
            'attr' => ['class' => 'form-control', 'placeholder' => 'Tapez le nom du produit']
        ])
        -> add('shortDescription', TextareaType::class, [
            'label' => 'Description courte',
            'attr' => [
                'class' => 'form-control', 
                'placeholder' => 'Tapez une description courte'
            ]
        ])
        -> add('price', MoneyType::class, [
            
        ])
        -> add('category');

        $form = $builder->getForm();

    $formView = $form->createView();

        return $this->render('product/create.html.twig', [
            'formView' => $formView
        ]);
    }
}      