<?php
/**
 * DataUpdater class
 * Used as a base class for a data source.
 */
namespace LockerHelper;

class DataUpdater
{

    public function __construct($city = null)
    {
		//TODO get lat/long of city, if defined
    }

    /**
     * Return a text representation of the object
     *
     * @return string object as text.
     */
    public function render()
    {
        //TODO render based on Reflection
        // output div tag with class = property name
        //TODO add '.png' extension for property names prefixed with 'img_'
        return "hello";
    }
}