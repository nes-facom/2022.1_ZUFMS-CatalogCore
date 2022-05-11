<?php

namespace App\DTO\Sheet;

class SheetDTO
{
    private $columnCount;
    private $rowCount;
    private $sheet;
    private $section;

    /**
     * @param $columnCount
     * @param $rowCount
     * @param $sheet
     * @param $section
     */
    public function __construct($columnCount, $rowCount, $sheet, $section)
    {
        $this->columnCount = $columnCount;
        $this->rowCount = $rowCount;
        $this->sheet = $sheet;
        $this->section = $section;
    }

    /**
     * @return mixed
     */
    public function getColumnCount()
    {
        return $this->columnCount;
    }

    /**
     * @return mixed
     */
    public function getRowCount()
    {
        return $this->rowCount;
    }

    /**
     * @return mixed
     */
    public function getSheet()
    {
        return $this->sheet;
    }

    /**
     * @return mixed
     */
    public function getSection()
    {
        return $this->section;
    }


}
