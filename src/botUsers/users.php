<?php
    ini_set('display_errors', 1);
    require '../../vendor/autoload.php';

    use Config\Database;
    use Service\Func;
    use Query\{
        Select,
        Insert
    };

    $db = new Database;
    $result = [];

    // Fetch the bot users from users.json
    $users = file_get_contents('users.json');
    // Decode it from json format to an object
    $users = json_decode($users);
    
    // Turning off the database untila process is fully completed
    $db->autocommit(FALSE);
    
    $subject = ["token", "fullname", "username", "email", "password", "timesLogged", "date", "time", "photo"];

    foreach($users as $ind => $user) {
        $date = Func::dateFormat();
        $one = 1;
        $token = Func::tokenGenerator();
        $pass = password_hash('demousers1', PASSWORD_DEFAULT);

        // initializing the items array for storing the user's data
        $items = [$pass, $one, $date, time(), "photo/default/user.png"];
        
        // convert the object to an array
        $user = json_decode(json_encode($user), true);
        // Combine both the firstname and the lastname
        $fullname = $user['first_name']." ".$user['last_name'];

        array_unshift($items, $token, $fullname, $user['username'], $user['email']);
        array_push($result, $items);

        // Resetting the items array for storing the users data to empty
        $items = [];
    }
    
    // Preparing to insert the data
    $inserting = $db->prepare("INSERT INTO user (token, fullname, username, email, password, timesLogged, date, time, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ");
    
    foreach($result as $values){
        $user = $values[2];
        $email = $values[3];
        
        // Check if this user with the same email or username already exists
        $data = [
            'email' => $email,
            "username" => $user,
            "needle" => "id",
            "table" => "user"
        ];

        $search = Func::searchDb($db, $data, "OR");
        if(!is_int($search)):
            // Saving the data in the user table
            $inserting->bind_param('sssssisis', ...$values);
            if($inserting->execute()):

                // Fetch the userid which would later be used to save in the logins table
                $userid = Func::searchDb($db, $data, "AND");

                $subject = ["user", "token", "device", "ip", "time"];
                $items = [$userid, Func::tokenGenerator(), Func::deviceInfo()[0], Func::deviceInfo()[1], time()];

                // Saving the user in the logins table, to register this device and IP
                $inserting1 = new Insert($db, "logins", $subject, "");
                $action = $inserting1->push($items, 'isssi');
                if($action):
                    $db->autocommit(TRUE);
                else:
                    return $action;
                endif;

            endif;
        endif;
    }

?>