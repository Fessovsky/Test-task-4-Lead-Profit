<?php
    /**
     * getNames from specified folder
     *
     * @param  string $folder (optional) by default looking for datafiles folder for search
     * @param  string $regExpPattern (optional) by default regex searching for .ixt files with Latin letters and numbers in the name.
     * @return void
     */
    function getNames($folder='', $regExpPattern = '~^[a-zA-Z0-9]+\.ixt$~'){
        if(!$folder){
            $folder = 'datafiles';
        }
        $counter=1;
        $namesArray = array();
        foreach(new DirectoryIterator($folder) as $file) {
            $fileName = $file->getFileName();
            if($fileName === '.' || $fileName === '..'){
                continue;
            }
            if( preg_match( $regExpPattern, $fileName )){
                echo "$counter. " . $fileName . PHP_EOL;
                array_push($namesArray, $fileName);
                $counter++;
            }
        }
        return $namesArray;
    }
    getNames();
    // getNames("", '~.~');
    
    