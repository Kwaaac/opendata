<?php


class SchoolCollection extends ArrayObject
{

    private array $schools = [];

    /**
     * SchoolCollection constructor.
     * @param array $schools
     */
    public function __construct(array $schools = [])
    {
        parent::__construct($schools);
    }

    public function addSchool (School $val, $key = null): void
    {
        if ($key === null) {
            $this->schools[] = $val;
        } else {
            $this->schools[$key] = $val;
        }
    }

    public function deleteSchool($key): void
    {
        unset($this->schools[$key]);
    }

    public function get($key): School
    {
        return $this->schools[$key];
    }

    public function offsetSet($index, $newval)
    {
        if (!(strcmp($newval.get_class(), School::getClass()) == 0)) {
            throw new \InvalidArgumentException("Must be int");
        }

        parent::offsetSet($index, $newval);
    }

}