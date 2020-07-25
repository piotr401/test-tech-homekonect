<?php

namespace App\Utils\Managers;

use App\Repository\ProductRepository;
use App\Utils\Managers\BaseManager;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ProductManager
 * `
 * Object manager of product
 *
 * @package App\Utils\Managers\ECommerce
 */
class ProductManager extends BaseManager
{

    /**
     * Repository
     *
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * ProductManager constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
        $this->addRepository('productRepository', Product::class);
    }

    /**
     * Save a product
     *
     * @param Product $product
     */
    public function save(Product $product)
    {
        $product->setUpdated(new \DateTime());
        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->flush();

        return $product;
    }

    /**
     * Remove one product
     *
     * @param Product $product
     */
    public function remove(Product $product)
    {
        $this->getEntityManager()->remove($product);
        $this->getEntityManager()->flush();
    }

    /**
     * Find all
     *
     * @return mixed
     */
    public function findAll()
    {
        return $this->productRepository->findAll();
    }

    /**
     * Find one by
     *
     * @param array $filters
     * @return mixed
     */
    public function findOneBy($filters = array())
    {
        return $this->productRepository->findOneBy($filters);
    }
}
