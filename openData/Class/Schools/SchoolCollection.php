<?php

require_once ('Class/Schools/School.php');
class SchoolCollection extends ArrayObject
{

    private $schools = [];

    /**
     * SchoolCollection constructor.
     * @param array $schools
     */
    public function __construct(array $schools = [])
    {
        parent::__construct($schools);
    }

    public function addSchool (School $val, $key = null)

    {
        if ($key === null) {
            $this->schools[] = $val;
        } else {
            $this->schools[$key] = $val;
        }
    }


    public function count()
    {
        $res = 0;
        foreach ($this->schools as $school){
            $res++;
        }

        return $res;
    }

    public function deleteSchool($key)
    {
        unset($this->schools[$key]);
    }

    public function get($key)
    {
        return $this->schools[$key];
    }

    public function getArray(){
        return $this->schools;
    }

    public function offsetSet($index, $newval)
    {
        if (!(strcmp($newval.get_class(), School::getClass()) == 0)) {
            throw new \InvalidArgumentException("Must be School");
        }

        parent::offsetSet($index, $newval);
    }

}