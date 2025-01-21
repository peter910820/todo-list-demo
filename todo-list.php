<?php
$username = 'root';
$password = 'Seaotter20170130';
try{
    $db = new PDO("mysql:host=localhost;dbname=todo-list",$username,$password);
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo "MySQL errors: " . $e->getMessage();
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $time = time_insert();
    data_insert($db,$time,$_POST['things'],$_POST['description']);
    show_database($db);
}else{
    show_database($db);
}

function show_database($db){
    
    echo <<< HTMLBLOCK
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>Todo-list</title>
    </head>
    <body>
    <h1>Todo-list</h1>
    <form class="form-inline" method="POST" action="$_SERVER[PHP_SELF]">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="things">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="description">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Insert</button>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">things</th>
                <th scope="col">description</th>
                <th scope="col">date</th>
            </tr>
        </thead>
    <tbody>
    HTMLBLOCK;
    try{
        $q = $db->query('SELECT * FROM todo_list');
        while($row = $q->fetch()){
            echo "<tr><th scope='row'>$row[id]</th><td class='table-success'>$row[things]</td><td class='table-primary'>$row[description]</td><td class='table-info'>$row[date]</td></tr>";
        }
    }catch(Throwable $e){
        echo "Errors: ". $e-> getMessage();
    }
    echo <<< HTMLBLOCK
    </tbody>
    </table>
    </body>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </html>
    HTMLBLOCK;
}

function data_insert($db,$time,$things,$description){
    try{
        $conn = $db->prepare('INSERT INTO todo_list (things, description,date) VALUES (?,?,?)');
        $conn->execute(array($things, $description,$time));
    }catch(PDOException $e){
        echo "Error: " . $e->getMessage();
    }
}

function time_insert(){
    date_default_timezone_set("Asia/Taipei");
    $time = date("Y.m.d ") . date("h:i:sa");
    return $time;
}   
?>