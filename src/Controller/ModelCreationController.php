<?php

namespace Controller;

use Model\ModelPc;
use Service\ModelFactory;
use Model\GraphicCard;
use Model\HardDisc;
use Model\Keyboard;
use Model\MouseAndPad;
use Model\PowerSupply;
use Model\Processor;
use Model\Ram;
use Model\Screen;
use Model\MotherBoard;
use PDO;

class ModelCreationController extends AbstractController
{

    public function getContent(): array
    {

        $ModelFactory = new ModelFactory($_POST, $this->db);
        if ($ModelFactory->modelIsValid()) {
            $modelPc = $ModelFactory->handleData();
            $this->insertModelBDD($modelPc);
        }

        $Components = $this->getComponents();


        return ["Components" => $Components, "ModelFactory" => $ModelFactory];
    }

    public function getFileName(): string
    {
        return 'ModelCreation';
    }

    public function getPageTitle(): string
    {
        return 'Creation modèle !';
    }

    public function getComponents()
    {
        $classes = [
            "GraphicCard" => GraphicCard::class,
            "PowerSupply" => PowerSupply::class,
            "HardDisc" => HardDisc::class,
            "Keyboard" => Keyboard::class,
            "MouseAndPad" => MouseAndPad::class,
            "Processor" => Processor::class,
            "Ram" => Ram::class,
            "Screen" => Screen::class,
            "MotherBoard" => MotherBoard::class,
        ];

        $sql = "SELECT  c.idComponent, category FROM component as c
    ";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();
        foreach ($results as $result) {

            $class = $classes[$result["category"]];
            $id = $result["idComponent"];
            $TableName = $result["category"];
            $sqlClass = 'SELECT * FROM Component as c 
                            INNER JOIN ' . $TableName . '  AS g ON c.idComponent = g.idComponent WHERE c.idComponent=' . $id;
            $statementClass = $this->db->prepare($sqlClass);
            // $statementClass->bindValue(":id", $id);
            $statementClass->setFetchMode(PDO::FETCH_CLASS, $class);

            $statementClass->execute();
            $Component = $statementClass->fetch();
            $Components[] = $Component;

        }
        return $Components;
    }

    public function insertModelBDD(ModelPc $modelPc)
    {
        $sqlModel = "INSERT INTO Model (name,pcNumber,price,quantity,addDate,isArchived) VALUES (:name,:pcNumber,:price,:quantity,:addDate,:isArchived)";
        $statementModel = $this->db->prepare($sqlModel);
        $statementModel->bindValue(":name", $modelPc->getName());
        $statementModel->bindValue(":pcNumber", $modelPc->getPcNumber());
        $statementModel->bindValue(":price", $modelPc->getPrice());
        $statementModel->bindValue(":quantity", $modelPc->getQuantity());
        $statementModel->bindValue(":addDate", $modelPc->getAddDate());
        $statementModel->bindValue(":isArchived", $modelPc->getIsArchived());
        $statementModel->execute();

        $id = $this->db->lastInsertId();
        $id = intval($id);
        var_dump($id);

        $sqlIntermediaryTable = "INSERT INTO modelcomponent (idModel,idComponent) VALUES (:idModel,:idComponent)";
        $statementTable = $this->db->prepare($sqlIntermediaryTable);

        foreach ($modelPc->getConfiguration() as $composant) {
            var_dump($composant->getIdComponent());
            $statementTable->bindValue(":idComponent", $composant->getIdComponent());
            $statementTable->bindValue(":idModel", $id);
            $statementTable->execute();
        }
    }
}