<?php
namespace AgileStoreLocator\Admin\CSV;


use AgileStoreLocator\Admin\CSV\base; 


class Reader extends base
{
    /**
     * Input encoding.
     *
     * @var string
     */
    private $inputEncoding = 'UTF-8';

    /**
     * Delimiter.
     *
     * @var string
     */
    private $delimiter;

    /**
     * Enclosure.
     *
     * @var string
     */
    private $enclosure = '"';

    /**
     * Sheet index to read.
     *
     * @var int
     */
    private $sheetIndex = 0;

    /**
     * Load rows contiguously.
     *
     * @var bool
     */
    private $contiguous = false;

    /**
     * Row counter for loading rows contiguously.
     *
     * @var int
     */
    private $contiguousRow = -1;

    /**
     * The character that can escape the enclosure.
     *
     * @var string
     */
    private $escapeCharacter = '\\';

    /**
     * [$rows CSV Data rows]
     * @var [type]
     */
    private $_rows;


    private $has_empty_rows = false;

    /**
     * Writing methods
     */
    const FILE = 'file';
    const DOWNLOAD = 'download';
    const STRING = 'string';
    

    /**
     * Create a new CSV Reader instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set input encoding.
     *
     * @param string $pValue Input encoding, eg: 'UTF-8'
     *
     * @return Csv
     */
    public function setInputEncoding($pValue)
    {
        $this->inputEncoding = $pValue;

        return $this;
    }

    /**
     * Get input encoding.
     *
     * @return string
     */
    public function getInputEncoding()
    {
        return $this->inputEncoding;
    }

    /**
     * Move filepointer past any BOM marker.
     */
    protected function skipBOM()
    {
        rewind($this->fileHandle);

        switch ($this->inputEncoding) {
            case 'UTF-8':
                fgets($this->fileHandle, 4) == "\xEF\xBB\xBF" ?
                    fseek($this->fileHandle, 3) : fseek($this->fileHandle, 0);

                break;
            case 'UTF-16LE':
                fgets($this->fileHandle, 3) == "\xFF\xFE" ?
                    fseek($this->fileHandle, 2) : fseek($this->fileHandle, 0);

                break;
            case 'UTF-16BE':
                fgets($this->fileHandle, 3) == "\xFE\xFF" ?
                    fseek($this->fileHandle, 2) : fseek($this->fileHandle, 0);

                break;
            case 'UTF-32LE':
                fgets($this->fileHandle, 5) == "\xFF\xFE\x00\x00" ?
                    fseek($this->fileHandle, 4) : fseek($this->fileHandle, 0);

                break;
            case 'UTF-32BE':
                fgets($this->fileHandle, 5) == "\x00\x00\xFE\xFF" ?
                    fseek($this->fileHandle, 4) : fseek($this->fileHandle, 0);

                break;
            default:
                break;
        }
    }

    /**
     * Identify any separator that is explicitly set in the file.
     */
    protected function checkSeparator()
    {
        $line = fgets($this->fileHandle);
        if ($line === false) {
            return;
        }


        if ((strlen(trim($line, "\r\n")) == 5) && (stripos($line, 'sep=') === 0)) {

            $this->delimiter = substr($line, 4, 1);
            return;
        }

        $this->skipBOM();
    }

    /**
     * Infer the separator if it isn't explicitly set in the file or specified by the user.
     */
    protected function inferSeparator()
    {
        if ($this->delimiter !== null) {
            return;
        }

        $potentialDelimiters = [',', ';', "\t", '|', ':', ' ', '~'];
        $counts = [];
        foreach ($potentialDelimiters as $delimiter) {
            $counts[$delimiter] = [];
        }

        // Count how many times each of the potential delimiters appears in each line
        $numberLines = 0;
        while (($line = $this->getNextLine()) !== false && (++$numberLines < 1000)) {
            $countLine = [];
            for ($i = strlen($line) - 1; $i >= 0; --$i) {
                $char = $line[$i];
                if (isset($counts[$char])) {
                    if (!isset($countLine[$char])) {
                        $countLine[$char] = 0;
                    }
                    ++$countLine[$char];
                }
            }
            foreach ($potentialDelimiters as $delimiter) {
                $counts[$delimiter][] = isset($countLine[$delimiter])? $countLine[$delimiter]: 0;
            }
        }

        // If number of lines is 0, nothing to infer : fall back to the default
        if ($numberLines === 0) {
            $this->delimiter = reset($potentialDelimiters);
            $this->skipBOM();

            return;
        }

        // Calculate the mean square deviations for each delimiter (ignoring delimiters that haven't been found consistently)
        $meanSquareDeviations = [];
        $middleIdx = floor(($numberLines - 1) / 2);

        foreach ($potentialDelimiters as $delimiter) {
            $series = $counts[$delimiter];
            sort($series);

            $median = ($numberLines % 2)
                ? $series[$middleIdx]
                : ($series[$middleIdx] + $series[$middleIdx + 1]) / 2;

            if ($median === 0) {
                continue;
            }

            $meanSquareDeviations[$delimiter] = array_reduce(
                $series,
                function ($sum, $value) use ($median) {
                    return $sum + pow($value - $median, 2);
                }
            ) / count($series);
        }

        // ... and pick the delimiter with the smallest mean square deviation (in case of ties, the order in potentialDelimiters is respected)
        $min = INF;
        foreach ($potentialDelimiters as $delimiter) {
            if (!isset($meanSquareDeviations[$delimiter])) {
                continue;
            }

            if ($meanSquareDeviations[$delimiter] < $min) {
                $min = $meanSquareDeviations[$delimiter];
                $this->delimiter = $delimiter;
            }
        }

        // If no delimiter could be detected, fall back to the default
        if ($this->delimiter === null) {
            $this->delimiter = reset($potentialDelimiters);
        }

        $this->skipBOM();
    }

    /**
     * Get the next full line from the file.
     *
     * @param string $line
     *
     * @return bool|string
     */
    private function getNextLine($line = '')
    {
        // Get the next line in the file
        $newLine = fgets($this->fileHandle);

        // Return false if there is no next line
        if ($newLine === false) {
            return false;
        }

        // Add the new line to the line passed in
        $line = $line . $newLine;

        // Drop everything that is enclosed to avoid counting false positives in enclosures
        $enclosure = '(?<!' . preg_quote($this->escapeCharacter, '/') . ')'
            . preg_quote($this->enclosure, '/');
        $line = preg_replace('/(' . $enclosure . '.*' . $enclosure . ')/Us', '', $line);

        // See if we have any enclosures left in the line
        // if we still have an enclosure then we need to read the next line as well
        if (preg_match('/(' . $enclosure . ')/', $line) > 0) {
            $line = $this->getNextLine($line);
        }

        return $line;
    }

    /**
     * Return worksheet info (Name, Last Column Letter, Last Column Index, Total Rows, Total Columns).
     *
     * @param string $pFilename
     *
     * @throws Exception
     *
     * @return array
     */
    public function getData($pFilename)
    {
        ini_set('auto_detect_line_endings', TRUE);


        $output_encoding = 'UTF-8';//UTF-16LE
        $input_encoding  = 'UTF-8';

        // Open file
        if (!$this->canRead($pFilename)) {
            throw new \Exception($pFilename . ' is an Invalid Spreadsheet file.');
        }
        $this->openFile($pFilename);
        $fileHandle = $this->fileHandle;
 

        // Skip BOM, if any
        $this->skipBOM();
        $this->checkSeparator();
        $this->inferSeparator();


        $all_rows = [];

        // Loop through each line of the file in turn
        while (($rowData = fgetcsv($fileHandle, 0, $this->delimiter, $this->enclosure, $this->escapeCharacter)) !== false) {
            
            foreach($rowData as &$rowDatum) {

                if(!is_numeric($rowDatum))
                    $rowDatum = mb_convert_encoding($rowDatum, $output_encoding);
            }

            $all_rows[] = $rowData;
        }
       
        // Close file
        fclose($fileHandle);
        
        $this->_rows = $all_rows;
    }

   

    /**
     * Get delimiter.
     *
     * @return string
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * Set delimiter.
     *
     * @param string $delimiter Delimiter, eg: ','
     *
     * @return CSV
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    /**
     * Get enclosure.
     *
     * @return string
     */
    public function getEnclosure()
    {
        return $this->enclosure;
    }

    /**
     * Set enclosure.
     *
     * @param string $enclosure Enclosure, defaults to "
     *
     * @return CSV
     */
    public function setEnclosure($enclosure)
    {
        if ($enclosure == '') {
            $enclosure = '"';
        }
        $this->enclosure = $enclosure;

        return $this;
    }

    /**
     * Get sheet index.
     *
     * @return int
     */
    public function getSheetIndex()
    {
        return $this->sheetIndex;
    }

    /**
     * Set sheet index.
     *
     * @param int $pValue Sheet index
     *
     * @return CSV
     */
    public function setSheetIndex($pValue)
    {
        $this->sheetIndex = $pValue;

        return $this;
    }

    /**
     * Set Contiguous.
     *
     * @param bool $contiguous
     *
     * @return Csv
     */
    public function setContiguous($contiguous)
    {
        $this->contiguous = (bool) $contiguous;
        if (!$contiguous) {
            $this->contiguousRow = -1;
        }

        return $this;
    }

    /**
     * Get Contiguous.
     *
     * @return bool
     */
    public function getContiguous()
    {
        return $this->contiguous;
    }

    /**
     * Set escape backslashes.
     *
     * @param string $escapeCharacter
     *
     * @return $this
     */
    public function setEscapeCharacter($escapeCharacter)
    {
        $this->escapeCharacter = $escapeCharacter;

        return $this;
    }

    /**
     * Get escape backslashes.
     *
     * @return string
     */
    public function getEscapeCharacter()
    {
        return $this->escapeCharacter;
    }

    /**
     * Can the current IReader read the file?
     *
     * @param string $pFilename
     *
     * @return bool
     */
    public function canRead($pFilename)
    {
        // Check if file exists
        try {
            $this->openFile($pFilename);
        } catch (\Exception $e) {
            return false;
        }

        fclose($this->fileHandle);

        // Trust file extension if any
        $extension = strtolower(pathinfo($pFilename, PATHINFO_EXTENSION));
        if (in_array($extension, ['csv', 'tsv'])) {
            return true;
        }

        // Attempt to guess mimetype
        $type = mime_content_type($pFilename);
        $supportedTypes = [
            'text/csv',
            'text/plain',
            'inode/x-empty',
        ];

        return in_array($type, $supportedTypes, true);
    }


    /**
     * [fillKeys Fill the Array Keys]
     * @param  [type] $keys [description]
     * @return [type]       [description]
     */
    public function fillKeys($columns) {

        //  Header Array
        $headers = array_shift($this->_rows);
 
        //  Collection of Rows
        $all_rows = [];

        if(is_array($headers)) {

            // Trim the array
            $headers = array_map('trim', $headers);

            $headers = array_values(array_unique(array_merge($headers, $columns)));
        }



        //  Count of Header Row Items
        $header_count    = count($headers);

        //  Rows count of the empty rows
        $empty_row_count = 0;

        foreach($this->_rows as $row) {

            if(is_array($row)) {
                
                $item_count = count($row);

                //  Slice array when row item is greater than the header
                if($item_count > $header_count) {

                    $row = array_slice($row, 0, $header_count);
                }
                //  When columns are missing at the end PAD it
                else if($item_count < $header_count) {

                    $row = array_pad($row, $header_count, '');
                }

                $all_rows[] = array_combine($headers, $row);
            }
        }


        //  set new results
        $this->_rows = $all_rows;
    }


    /**
     * [getHeaderRowCount description]
     * @return [type] [description]
     */
    public function getHeaderRowCount() {


        if($this->_rows && $this->_rows[0]) {

            return count($this->_rows[0]);
        }

        return 0;
    }


    /**
     * Get data rows
     * @return array
     */
    public function getRows() {
        return $this->_rows;
    }
    
    /**
     * Set data rows
     * 
     * Use to load PHP associative array data for CSV file creation
     * 
     * @param array $rows
     */
    public function setRows($rows) {
        $this->_rows = $rows;
    }
    
    /**
     * Write CSV file from PHP array data
     * 
     * There are 3 output methods for CSV data:
     *  - As a physical file (pass a full path as the second parameter)
     *  - As a forced download file (pass the file name as the second parameter)
     *  - As a string (returns the CSV file as a string)
     * 
     * Use class constants for easy access to the method values.
     * 
     * @param string $method
     * @param string $filename
     * @return string/null
     */
    public function write($method = self::STRING, $filename = false) {
            
        ob_start();

        $handle = fopen('php://output','w');        
        
        $header_keys  = [];
        
        //  Set the first Row as header
        if(isset($this->_rows) && isset($this->_rows[0])) {

            $first_row   = (Array) $this->_rows[0];

            $header_keys = array_keys($first_row);

            fputcsv($handle, $header_keys);
        }

        //  Loop over the data
        foreach($this->_rows as $row) {

            $row_arr = (Array) $row;    

            fputcsv($handle, $row_arr);
        }

        fclose($handle);

        $csv = ob_get_clean();


        switch($method) {
            default:
            case self::STRING:
                return $csv;
            case self::FILE : 
                file_put_contents($filename, $csv);
                break;
            case self::DOWNLOAD :
                self::download($csv,$filename);
                break;
        }       
    }
    
    /**
     * Start forced download in the browser
     * @param string $string
     * @param string $filename
     */
    protected static function download($string, $filename) {
        $ctype = 'application/force-download';
        header("Cache-Control: cache, must-revalidate");
        header("Pragma: public");
        header('Content-Type: ' . $ctype);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: '.mb_strlen($string,'latin1'));
        echo $string;
    }
}
