<?php 

Class CsvReader {

    private $readableFileName, $chunkSize, $writeableFileName, $csvWriter;
    public $records, $combinations, $headers, $secondCombinations, $finalCombinations;

    /**
     * This class is responisable to read csv file and generate combincations
     *
     * @param  AssociativeArray
     * @param  AssociativeArray
     * 
     * @return void
     */
    public function __construct($readableFile, $writeableFile) {
        ini_set('memory_limit', '-1');
        $this->chunkSize = 10000;
        $this->readableFileName = $readableFile;
        $this->writeableFileName = $writeableFile;
        $this->records = $this->combinations = $this->headers = $this->secondCombinations = $this->finalCombinations = [];
        $this->readFile();
    }


    /**
     * This function is responisable read file in chunk as pas on each row a combinator function.
     *
     * @return void
     */
    private function readFile()
    {
        try {
            $chunkCount = 0;

            if (file_exists($this->readableFileName['name'])) {
                # Open the CSV file for reading
                if (($handle = fopen($this->readableFileName['name'], "r")) !== false) {
                    # Read the header row to use as keys
                    $this->headers = fgetcsv($handle);
                    $this->exicuteCsvWrite();
                    $chunkCount = 0;

                    while (!feof($handle)) {
                        # Initialize an array to store the current chunk of rows
                        $chunk = [];

                        # Read and process each row in the chunk
                        for ($i = 0; $i < $this->chunkSize; $i++) {
                            $row = fgetcsv($handle);
                            if ($row === false) {
                                break;
                            }
                            $row_data = array_combine($this->headers, $row);
                            $this->records[] = $chunk[] = $row_data;
                        }

                        $chunkCount = ++$chunkCount;
                       
                        $this->combinations($chunk);
                        $this->secondCombinations = array_merge($this->secondCombinations,$this->combinations);

                        unset($this->combinations);
                        $this->combinations=[];
                        var_dump('A chunk completed no. ' . $chunkCount);
                    }
                    # Close the file
                    fclose($handle);
                    $this->makeFinalCombination();
                    $this->exicuteCsvWrite();
                } else {
                    throw new Exception("Failed to open the CSV file.");
                }
           
            } else {
                throw new Exception("The CSV file does not exist.");
            }

        } catch (Exception $e) {
            throwError($e->getMessage());
        }
    }

    /**
     * This function is make final combinations of each chunk.
     *
     * @return void
     */
    private function makeFinalCombination()
    {
        $temp = $this->secondCombinations; 

        foreach($temp  as $index1 => $combination1)
        {
            $count = 0;
            $findings = [];

            $temp1 = $combination1;
            unset($temp1['count']);
            foreach($temp  as $index2 => $combination2)
            {
                $temp2 = $combination2;
                unset($temp2['count']);
    
                if(array_values($temp2) === array_values($temp1))
                {
                    $findings[] = $combination2;
                    unset($this->secondCombinations[$index2]);
                }
            }
            foreach($findings as $finding)
            {
                $count = $count + $finding['count'];
            }
            
            $findings[0]['count'] = $count;
            $this->finalCombinations[] = (array)$findings[0];
            break;
        }

        while(!empty($this->secondCombinations))
        {
            var_dump(count($this->secondCombinations));

            $this->makeFinalCombination();
        }
    }

    /**
     * This function exicute csv file to insert all the combination.
     *
     * @return void
     */
    private function exicuteCsvWrite()
    {
        $this->csvWriter = new CsvWriter($this->writeableFileName['name']);
        $headers = $this->headers;
        array_push($headers,'count');
        $this->csvWriter->addRecord($this->finalCombinations, $headers);
        $this->csvWriter->close();
    }

    /**
     * This function make combinations from each chuck & merge all combination
     *
     * @param  AssociativeArray
     * @return void
     */
    private function combinations($chunks)
    {
        foreach($chunks as $row_data)
        {
            if(count($this->combinations) == 0)
            {
                $row_data += ['count' => 1];
                $this->combinations[] = $row_data;
            }else{
                
                $found = false;
    
                foreach($this->combinations as $index => $combination)
                {
                    $findings = [];
                    unset($combination['count']);
                    $found = array_values($combination) === array_values($row_data);
                    
                    if($found) break;
                }
                if($found)
                {
                    $this->combinations[$index]['count'] = ++$this->combinations[$index]['count'];
                }else{
                    $row_data += ['count' => 1];
                    $this->combinations[] = $row_data;
                }
            }
        }
    }
}

?>
