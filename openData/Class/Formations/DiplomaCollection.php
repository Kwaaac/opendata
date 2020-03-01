<?php

require_once ('Class/Schools/School.php');
class DiplomaCollection extends ArrayObject
{

    private $diplomas = [];

    /**
     * SchoolCollection constructor.
     * @param array $diplomas
     */
    public function __construct(array $diplomas = [])
    {
        parent::__construct($diplomas);
    }

    public function addDiploma (Diploma $val, $key = null)
    {

        if ($key === null) {
            $this->diplomas[] = $val;
        } else {
            $this->diplomas[$key] = $val;
        }
    }

    public function deleteDiploma($key)
    {
        unset($this->diplomas[$key]);
    }

    public function count()
    {
        $res = 0;
        foreach ($this->diplomas as $diploma){
            $res++;
        }

        return $res;
    }

    public function get($key)
    {
        return $this->diplomas[$key];
    }

    public function getArray(){
        return $this->diplomas;
    }

    public function offsetSet($index, $newval)
    {
        if (!(strcmp($newval.get_class(), Diploma::getClass()) == 0)) {
            throw new \InvalidArgumentException("Must be int");
        }

        parent::offsetSet($index, $newval);
    }

}