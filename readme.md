# Database Class

The Database class provides a simple and convenient way to interact with a MySQL database using PHP's PDO (PHP Data Objects) extension. It encapsulates common database operations such as fetching records, inserting new records, and updating existing records.

## Getting Started

1. Include the Database class file in your PHP script:

```php
require_once 'Database.php';
```

2. Create a new instance of the Database class:

```php
$db = new Database();
```

3. You're ready to use the Database class to perform database operations!

## Functions

### fetch

```php
public function fetch($tableName, $columns = array(), $conditions = array(), $orderBy = array())
```

This function retrieves records from a specified table in the database.

- `$tableName` (string): The name of the table.
- `$columns` (array): Optional. An array of column names to fetch. If not provided, all columns will be fetched.
- `$conditions` (array): Optional. An array of conditions to filter the records. The array should be in the format `column => value`. Multiple conditions can be specified by using the same column name with different values. You can also use the `!` symbol in front of a column name to indicate a not-equal condition.
- `$orderBy` (array): Optional. An array of column names to sort the records. You can use the `!` symbol in front of a column name to indicate descending order.

Example usage:

```php
$result = $db->fetch("cars", array("car_make", "car_model"), array("car_make" => array("BMW", "Audi")), array("car_make", "!car_model"));
foreach ($result as $row) {
    Process each record
}
```

### insert

```php
public function insert($tableName, $data)
```

This function inserts a new record into the specified table in the database.

- `$tableName` (string): The name of the table.
- `$data` (array): An associative array where the keys represent the column names and the values represent the data to be inserted.

Example usage:

```php
$data = array(
    "car_make" => "BMW",
    "car_model" => "X5",
    "car_price" => 50000
);
$insertId = $db->insert("cars", $data);
```

### update

```php
public function update($tableName, $data, $conditions = array())
```

This function updates existing records in the specified table in the database.

- `$tableName` (string): The name of the table.
- `$data` (array): An associative array where the keys represent the column names to be updated and the values represent the new data.
- `$conditions` (array): Optional. An array of conditions to filter the records that need to be updated. The array should be in the format `column => value`.

Example usage:

```php
$data = array(
    "car_price" => 55000
);
$affectedRows = $db->update("cars", $data, array("car_make" => "BMW"));
```

### getError

```php
public function getError()
```

This function returns the error message of the last database operation.

Example usage:

```php
$error = $db->getError();
```

### getLastQuery

```php
public function getLastQuery()
```

This function returns the last executed SQL query.

Example usage:

```php
$query = $db->getLastQuery();
```

## Conclusion

The Database class simplifies database operations by providing a clean and reusable interface. By using the class's functions, you can easily fetch, insert, and update records in your MySQL database.
