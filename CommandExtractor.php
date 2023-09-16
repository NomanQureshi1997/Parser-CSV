<?php

class CommandExtractor {

    protected $argc, $argv, $allowedFileTypes, $fileOneExplode, $fileTwoExplode, $stringPattern;
    public $readableFile, $writeableFile;

    /**
     * This class has two major functionalities as follows.
     * 1. Validate input command.
     * 2. retrive file name from the command.
     * The constructor will call the variable & functions in sequence to complete the process.
     *
     * @return void
     */
    public function __construct($argc, $argv) {
        $this->argc = $argc;
        $this->argv = $argv;
        $this->readableFile = $this->writeableFile = [
            'name' => '',
            'type' => ''
        ];
        $this->stringPattern = '/^[a-zA-Z0-9._]+$/'; #File name must not contain special characters excpet '.' or '_'
        $this->allowedFileTypes = ['csv'];
        $this->validateCommand();
        $this->validateCommandParams();
        $this->setFileInfo();
        $this->validateReadableFile();
    }

    /**
     * This private function in responsible to fetch out file name file type from command.
     * For now the code will only read csv files
     *
     * @return void
     */
    private function setFileInfo()
    {
        $this->fileOneExplode = explode("=", $this->argv[1]);
        $this->fileTwoExplode = explode("=", $this->argv[2]);

        !preg_match($this->stringPattern, $this->fileOneExplode[1]) && throwError('Usage: php parser.php --file Special characters are not permitted, and the file must be located in the same folder.');
        !preg_match($this->stringPattern,  $this->fileTwoExplode[1]) && throwError('Usage: php parser.php --unique-combinations Special characters are not permitted, and the file must be located in the same folder.');

        $this->readableFile = [ 
            'name' => $this->fileOneExplode[1],
            'type' => strtolower(pathinfo($this->fileOneExplode[1], PATHINFO_EXTENSION))
        ];
        $this->writeableFile = [ 
            'name' => $this->fileTwoExplode[1],
            'type' => strtolower(pathinfo($this->fileTwoExplode [1], PATHINFO_EXTENSION))
        ];
        return;
    }

    /**
     * This private function will validate file type which must be a csv file.
     * In case, it's not csv file code will exit and throw an exception on command line
     *
     * @return void
     */
    private function validateReadableFile()
    {
        switch($this->fileOneExplode[1]) {
            case "":
                throwError('Usage: php parser.php, File path is missing');
            case $this->readableFile['type'] == "":
                throwError('Usage: php parser.php, File type is missing');
            case !in_array($this->readableFile['type'], $this->allowedFileTypes):
                throwError('Usage: php parser.php, Only '. implode(",",$this->allowedFileTypes) . ' are accpetable');
            case !in_array($this->writeableFile['type'], $this->allowedFileTypes):
                throwError('Usage: php parser.php, Only '. implode(",",$this->allowedFileTypes) . ' are accpetable');
            default:
            return;
        }
    }

    /**
     * This private function will validate if params of commands is less than 3
     * In case of less than 3 code will exit and throw an exception on command line
     *
     * @return void
     */
    private function validateCommand() {

        # This will validate if require scrip has all 3 param If not it retrun error
        $this->argc < 3 && throwError('Usage: php parser.php --file <input_file> --unique-combinations <output_file>');
      
        return;
    }

    
    /**
     * This private function will validate two params --file= & --unique-combinations=
     * In case of error code will exit and throw an exception on command line
     *
     * @return void
     */
    private function validateCommandParams()
    {
        switch($this->argv) {
            case !str_contains($this->argv[1], '--file='): 
                throwError('Usage: php parser.php --file is missing');

            case !str_contains($this->argv[2], '--unique-combinations='): 
                throwError('Usage: php parser.php --unique-combinations is missing');

            case str_contains($this->argv[1], ' '): 
                throwError('Usage: php parser.php, There should no space');

            default:
                return;
        }
    }
}

?>