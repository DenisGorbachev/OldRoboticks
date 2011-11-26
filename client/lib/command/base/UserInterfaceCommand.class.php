<?php

require_once dirname(__FILE__).'/ServerCommand.class.php';

abstract class UserInterfaceCommand extends ServerCommand {
    public $empty_cell_placeholder = '-';
	
    public function table($table, $sortByColumnIndex = false)
    {
        if ($sortByColumnIndex) {
            usort($table, function($a, $b) use ($sortByColumnIndex)
                {
                    if ($a[$sortByColumnIndex] == $b[$sortByColumnIndex]) {
                        return 0;
                    }
                    return ($a[$sortByColumnIndex] < $b[$sortByColumnIndex]) ? -1 : 1;
                }
            );
        }

        foreach ($table as $row) {
            foreach ($row as $columnIndex => $cell) {
                if (empty($maxlengths[$columnIndex])) {
                    $maxlengths[$columnIndex] = 0;
                }
                if (mb_strlen((string)$cell) > $maxlengths[$columnIndex]) {
                    $maxlengths[$columnIndex] = mb_strlen($cell);
                }
            }
        }

        foreach ($table as $row) {
            $rowString = '';
            foreach ($row as $columnIndex => $cell) {
                $rowString .= str_pad((string)$cell, min(40, $maxlengths[$columnIndex]) + 2); // spaces between columns
            }
            echo $rowString.PHP_EOL;
        }
    }

    public function sector($x, $y) {
        return $x.','.$y;
    }

    public function coords($sector) {
        return $this->sector($sector['x'], $sector['y']);
    }

}
