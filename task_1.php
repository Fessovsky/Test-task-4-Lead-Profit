<?php

// Задача №1. 
// Написать класс init, от которого нельзя сделать наследника, состоящий из 3 методов:
// - create() 
//   • доступен только для методов класса 
//   • создает таблицу test, содержащую 5 полей
//  
// - ﬁll() 
//   • доступен только для методов класса 
//   • заполняет таблицу случайными данными
//  
// - get() 
//   • доступен извне класса 
//   • выбирает из таблицы test, данные по критерию: 
//      result среди значений 'normal' и 'success' 
// В конструкторе выполняются методы create и ﬁll Весь код должен быть прокомментирован в стиле PHPDocumentor'а.

$dbConfig = json_decode(getenv('DB_CONFIG'));
/**
 * TEST TASK #1
 * 
 * 
 * Init build and fill table with customizable quantity of rows
 * @author github.com/fessovsky / isaev.dmi3@gmail.com
 */
final class Init {    
    /**
     * __construct
     *
     * @param  mixed $dbConfig
     * @return void
     * 
     */
    function __construct($dbConfig) {
        $this->connection = mysqli_connect('localhost', $dbConfig->DB_USER, $dbConfig->DB_PASSWORD, $dbConfig->DB_NAME);
        // $this->dropTable(); 
        $this->create();
        $this->fill(15);
    }    
    /**
     * create table
     * 
     * @return void
     */
    private function create() {
        $query = "CREATE TABLE `test` ( 
            `id` INT(11) NOT NULL AUTO_INCREMENT, 
            `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, 
            `some_data` INT NOT NULL , 
            `another_data` INT NOT NULL , 
            `result` VARCHAR(11) NOT NULL , 
            PRIMARY KEY (`id`)) ENGINE = InnoDB;";
        $result = mysqli_query( $this->connection, $query);
    }
    
    /**
     * fill private func populate table with randomized data. Use counter to control quantity of rows.
     * It randomly fill rows. Result column generated on the fly from list of 5 items.
     *
     * @param  int $counter
     * @return void
     */
    private function fill($counter=1) {
        $sqlInsert = "INSERT INTO `test` (`some_data`, `another_data`, `result`) VALUES ";
        
        while($counter>0){
            $sqlInsert .= "(RAND() * 15, RAND() * 15, ELT(0.5 + RAND() * 5, 'success', 'normal', 'failure', 'null', 'empty')),";
            $counter--;
        }

        $query = substr($sqlInsert, 0, -1) . ";";
        mysqli_query($this->connection, $query);
    }

        
    /**
     * dropTable temporary solution. For now it is a bug in mysqli_query function: when it can't find table it show fatal error instead of returning false.
     * Spent some time to find an issue, and don't have any time to find good solution to check if table already created.
     *
     * @return void
     */
    private function dropTable() {
        mysqli_query($this->connection, 'DROP TABLE `test`');
    }    
    /**
     * get get data from result column in created table.  
     *
     * @return void
     */
    public function get() {
        $query = "SELECT * FROM test WHERE `result` LIKE 'success' or `result` LIKE 'normal';"; 
        $result = mysqli_query($this->connection, $query);
        if ($result->num_rows > 0) {
            echo <<<TH
            <style>
                table {
                    background: lightgrey;
                }
                tr,th,td {
                    text-align: center;
                    border: 1px solid white;
                }
                </style>
            <div>
            <h3>Fetched data</h3>
            <table>
                <tr>
                    <th>Id</th>
                    <th>Date</th>
                    <th>Some data</th>
                    <th>Another data</th>
                    <th>Result</th>
                </tr>
            TH;
            while($row = $result->fetch_assoc()) {
                echo <<<TB
                <tr>
            <td>{$row['id']}</td>
            <td>{$row['date']}</td>
            <td>{$row['some_data']}</td>
            <td>{$row['another_data']}</td>
            <td>{$row['result']}</td>
            </tr>
            TB;
        }
        echo "</table>";
    } 
    else {
        echo "<h4>0 results</h4>";
    }
    
    }
}

$init = new Init($dbConfig);
$init->get();