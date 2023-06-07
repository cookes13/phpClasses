# **Database Class**
The Database class provides a simple and convenient way to interact with a MySQL database using PHP's PDO (PHP Data Objects) extension. It encapsulates common database operations such as fetching records, inserting new records, and updating existing records.

## **Getting Started**
1. Include the Database class file in your PHP script:
```php
require_once '/path/to/classes/folder/Database.php';
```
2. Add credentials to database.php file
```php
private $servername = "Database Server Address";
private $username = "Database User";
private $password = "Database Password";
private $database = "Database Name";
```

You're now ready to use the Database class to perform database operations!
## **Functions**
1. ### **Fetch**
```php
public function fetch($tableName, $columns = array(), $conditions = array(), $orderBy = array())
```
This function retrieves records from a specified table in the database.

- `$tableName` (string): The name of the table.
- `$columns` (array): Optional. An array of column names to fetch. If not provided, all columns will be fetched.
- `$conditions` (array): Optional. An array of conditions to filter the records. The array should be in the format: `column => value`. Multiple conditiions can be specified. To specify an OR statment on the same column create an array for the two values from the same column `"car_make" => array("BMW", "Audi")`.
- `$orderBy` (array): Optional. An array of column names to sort the records. You can use the ! symbol in front of a column name to indicate descending order.

#### Example usage:

```php
$db = new Database(); //Only 1 required per php file
$columns = array("car_make", "car_model");
$conditions = array("car_make" => array("BMW", "Audi"));
$order = array("car_make", "!car_model");
$result = $db->fetch("cars", $columns , $conditions, $order);
foreach ($result as $row) {
    // Process each record
}
```
2. ### **Insert**
```php
public function insert($tableName, $data)
```
This function inserts a new record into the specified table in the database.

- `$tableName` (string): The name of the table.
- `$data` (array): An associative array where the keys represent the column names and the values represent the data to be inserted.

#### Example usage:

```php
$db = new Database(); //Only 1 required per php file

$data = array(
    "car_make" => "BMW",
    "car_model" => "X5",
    "car_price" => 50000
);
$insertId = $db->insert("cars", $data);
```

3. ### **Update**
```php
public function update($tableName, $data, $conditions = array())
```
This function updates existing records in the specified table in the database.

- `$tableName` (string): The name of the table.
- `$data` (array): An associative array where the keys represent the column names to be updated and the values represent the new data.
- `$conditions` (array): Optional. An array of conditions to filter the records that need to be updated. The array should be in the format `column => value`.

#### Example usage:

```php
$db = new Database(); //Only 1 required per php file

$data = array(
    "car_price" => 55000
);
$affectedRows = $db->update("cars", $data, array("car_make" => "BMW"));
```

4. ### **getError**
```php
public function getError()
```
This function returns the error message of the last database operation.

#### Example usage:

```php
//Query above
$error = $db->getError();
if ($error) {
    // Handle the error
}
```
5. ### **getLastQuery**
```php
public function getLastQuery()
```
This function returns the last executed SQL query.

#### Example usage:

```php
//Query above
$query = $db->getLastQuery();
echo "Last query: " . $query;
```
- Note: It's recommended to use this function for debugging purposes only and not in production code, as it may expose sensitive information.
