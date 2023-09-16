<?php
require './CommandExtractor.php'; 
require './CsvReader.php'; 
require './Common.php'; 
require './CsvWriter.php';

$fileTypeDefinedClasses = [
    'csv' => 'CsvReader',
    'json' => 'JsonReader.php'
];

$GLOBALS['combinations'] = [];

$command_validator = new CommandExtractor($argc, $argv);

$records = new $fileTypeDefinedClasses[$command_validator->readableFile['type']](
    $command_validator->readableFile,
    $command_validator->writeableFile
);


return;
