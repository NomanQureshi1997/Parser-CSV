
# CSV parser

This algorithm has been developed to analyze a CSV file, identifying potential combinations within each row. Looking ahead, this code boasts individual, self-contained classes that can be easily adapted for various file formats, such as JSON, XML, and more. Making this code compatible with a new file type would only necessitate minor adjustments.

Let get started with quick run.

**Note:** read able file should be present in same directory.

**Command** php parser.php --file <input_file> --unique-combinations <output_file>

**example** php parser.php --file=products_comma_separated.csv --unique-combinations=combinations.csv

In few mintues you will see all the possiable combinations.

Let's Understand how codes word.

We have starter file or can be called as parent file parser.php
This file is responsable to inicidate classes & each class has it own responsibility to run it function.

**First** 
The parser.php called CommandExtractor.php, This class is responsibilities is as followings:
1. Validate command & params.
2. Find file names from params store it in a public variable from where any one can access.

**Second** 
The parser.php calls CsvReader.php. This class is responsibilities is as followings
1. Read file in chucks.
2. Process every chunk individually and find possiable combination from each chunk.
3. It merge all the chunk into a array e.g $secondCombination.
4. Now a function is resonsiable to finction all possiable combinations from $secondCombination, by using recursive function & nested loop and reduces the length array to minimize the execution time.

**Third**
The CsvReader.php calls CsvWriter.php. This class is an independent class which accpets and array that needs to dump in csv. This class is responsibilities is as followings
1. Open new or existing file.
2. Added headers.
3. Adds rows.
4. Close file
