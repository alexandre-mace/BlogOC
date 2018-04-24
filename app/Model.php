<?php

namespace App;

use Model\Post;
/**
* Class Model for all src/models
*/
abstract class Model
{
	public $originalData = [];

    private $errors = [];

	public abstract static function metadata();
	
	public abstract static function getManager();

    public function isValid() 
    {
        foreach($this->metadata()["columns"] as $name => $definition) {
            if (isset($definition["constraints"])) {
                foreach($definition["constraints"] as $type => $details) {

                    if ($type == "required" && !$this->{'get' . ucfirst($definition["property"])}()) {
                        $this->errors[] = $details["message"];
                    }
                    if ($type == "length" && isset($details["min"]) && strlen(trim($this->{'get' . ucfirst($definition["property"])}())) < $details["min"]) {
                        $this->errors[] = $details["minMessage"];
                    }
                                    
                    if ($type == "length" && isset($details["max"]) && strlen(trim($this->{'get' . ucfirst($definition["property"])}())) > $details["max"]) {
                        $this->errors[] = $details["maxMessage"];
                    }

                }
            }
        }
        return count($this->errors) == 0;
    }

    public function hydrate($result, $update = 0)
    {
        if(empty($result)) {
            return NULL;
        }

        if ($update = 1) {
            foreach ($this::metadata()["columns"] as $name => $definition) {
                $this->originalData[$name] = $definition["property"];
            }
            foreach($result as $column => $value) {
                $this->hydrateProperty($column, $value);
            }
        return $this;
        }

        foreach($result as $column => $value) {
            $this->originalData[$column] = $value;
            $this->hydrateProperty($column, $value);
        }
        return $this;
    }

    private function hydrateProperty($column, $value)
    {
        switch($this::metadata()["columns"][$column]["type"]) {
            case "integer":
                $this->{'set' . ucfirst($this::metadata()["columns"][$column]["property"])}($value);
                break;
            case "string":
                $this->{'set' . ucfirst($this::metadata()["columns"][$column]["property"])}($value);
                break;
            case "datetime":
                $datetime = \DateTime::createFromFormat("Y-m-d H:i:s", $value);
                $this->{'set' . ucfirst($this::metadata()["columns"][$column]["property"])}($datetime);
                break;
            case "model":
                $manager = Database::getInstance()->getManager('Model\\' . $this::metadata()["columns"][$column]["class"]);
                $object = $manager->find($value); 
                $this->{'set' . ucfirst($this::metadata()["columns"][$column]["property"])}($object);
        }
    }

	public function getSQLValueByColumn($column)
	{
		$value = $this->{'get' . ucfirst($this::metadata()["columns"][$column]["property"])}();
		if ($value instanceof \DateTime) {
			return $value->format("Y-m-d H:i:s");
		}
        if ($value instanceof Model) {
            return $value->getId();
        }
		return $value;
	}

    public function setPrimaryKey($value)
    {
        $this->hydrateProperty($this::metadata()["primaryKey"], $value);
    }

    public function getPrimaryKey()
    {
        $primaryKeyColumn = $this::metadata()["primaryKey"];
        $property = $this::metadata()["columns"][$primaryKeyColumn]["property"];
        return $this->{'get'. ucfirst($property)}();
    }

    public function getErrors()
    {
        return $this->errors;
    }
}