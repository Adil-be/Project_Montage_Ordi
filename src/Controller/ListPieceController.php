<?php
namespace Controller;

use Model\Component;
use PDO;
use Service\ListPieceFilter;

class ListPieceController extends AbstractController
{
    public function getContent(): array
    {
        // var_dump($trierResults);
        // brand sql
        $sqlBrand = "SELECT brand FROM component GROUP BY brand ORDER BY brand ASC";
        $brandStatement = $this->db->prepare($sqlBrand);
        $brandStatement->execute();
        $brandResults = $brandStatement->fetchAll(PDO::FETCH_ASSOC);

        // all components s
        $sql = "SELECT * FROM component";
        $params = []; // to add security avoiding database manipulation by users
        $criterias = []; // to add security avoiding database manipulation by users
        $filters = new ListPieceFilter($_POST);
        if (!empty($filters->getBrand())) {
            $criterias[] = 'brand = :brand';
            $params[':brand'] = $filters->getBrand(); // brand params 
        }
        if (!empty($filters->getCategory())) {
            $criterias[] = 'category = :category';
            $params[':category'] = $filters->getCategory(); //category params 
        }
        if (!empty($filters->getMinPrice())) {
            $criterias[] = 'price >= :minprice';
            $params[':minprice'] = $filters->getMinPrice(); //minprice params 
        }
        if (!empty($filters->getMaxPrice())) {
            $criterias[] = 'price <= :maxprice';
            $params[':maxprice'] = $filters->getMaxPrice(); //maxprice params 
        }

        if (!empty($criterias)) {
            $sql .= ' WHERE ' . implode(' AND ', $criterias);
        }

        // tout trier  
        // if (isset($_POST['trier'])) { 
        //     $tri = $_POST['trier'];

        //     // $sql = $sql . " ORDER BY " . $tri;   // SECOND OPTION OF SORTING
        //     $sql .= " ORDER BY " . $tri;
        // } else {
        //     $sql .= ' ORDER BY price ASC';
        // }
        if ($filters->getSortBy()) {
            // $sql = $sql . " ORDER BY " . $filters->getSortBy();
            $sql .= " ORDER BY " . $filters->getSortBy();
        } else {
            $sql .= ' ORDER BY price ASC';
        }

        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        $statement->setFetchMode(PDO::FETCH_CLASS, Component::class);
        $results = $statement->fetchAll();

        return [
            "results" => $results,
            "catResults" => Component::AVAILABLE_CATEGORIES,
            // fetching the consts category in database
            "brandResults" => $brandResults,
            "filters" => $filters,
        ];

    }

    public function getFileName(): string
    {
        return 'listPiece';
    }

    public function getPageTitle(): string
    {
        return 'List of Products available';
    }
    public function getMyfunction(): array
    {
        return [];
    }
}