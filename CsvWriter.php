<?php
class CsvWriter {
    private $outputFile;
    private $file;


    /**
     * This class is responisable to write csv file
     *
     * @return void
     */
    public function __construct($outputFile) {
        $this->outputFile = $outputFile;
    }

    public function createFile() {
        $this->file = fopen($this->outputFile, 'w'); # 'w' mode for writing
    }

    public function addHeaders($headers) {
        if ($this->file !== false) {
            fputcsv($this->file, $headers);
        } else {
            throw new Exception("The CSV file is not open for writing headers.");
        }
    }

    public function addRecord($combinations, $headers) {
        $this->file = fopen($this->outputFile, 'w');

        $this->addHeaders($headers);

        if ($this->file !== false) {
            foreach($combinations as $combination)
            {
                fputcsv($this->file, array_values($combination));
            }
        } else {
            throw new Exception("The CSV file is not open for writing records.");
        }
    }

    public function close() {
        if ($this->file !== false) {
            fclose($this->file);
        }
    }
}
?>