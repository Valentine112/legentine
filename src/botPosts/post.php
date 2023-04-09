<?php
    ini_set('display_errors', 1);
    require '../../vendor/autoload.php';

    use Config\Database;
    use Service\Func;
    use Query\{
        Select,
        Insert
    };

    /**
     * Creating bot post
     * And inserting them in to the database
     */

    $db = new Database;
    $result = [];
    $empty = [];
    $exist = [];

    // Fetch the post from post.json
    $posts = file_get_contents("post.json");
    // Decode it as it was already in a json format
    $posts = json_decode($posts);
    // Count the number of post as we would be looping and processing each of them
    $post_len = count($posts);

    // Fetch user id
    $selecting = new Select($db);

    $selecting->more_details("");
    $action = $selecting->action("id", "user");
    if($action != null) return $action;

    $values = $selecting->pull();

    if($values[1] > 0) {
        $id = $values[0];
    }
    $users = $values[0];
    $user_length = $values[1];

    // Got the maximum and minimum user id to randomly pick from them
    $min_id = min($values[0]);
    $max_id = max($values[0]);

    // Turned of the database until the whole process is completed
    $db->autocommit(FALSE);

    for($i = 0; $i < $post_len;) {
        // Get a random number between the 0 and the total length of the post
        // This random number would be used as an index to fetch a random post from the object
        $rand_post_index = random_int(0, ($post_len - 1));

        // Created a random number between 0 and the total length of users
        // This number would be used as the index to access users from the array randomnly
        $rand_user_index = random_int(0, ($user_length - 1));

        $subject = ["user", "token", "title", "content", "category", "comments_blocked", "privacy", "date", "time"];

        // Make sure the random number has not been picked before
        if(!in_array($rand_post_index, $exist)):
            // Decoded the object to an array format and picked a random post using the random number generated
            $post = json_decode(json_encode($posts[$rand_post_index]), true);
            array_push($exist, $rand_post_index);

            // Initiated our values
            // Fetching a random user from the array of users
            $rand_user = $users[$rand_user_index]['id'];
            $zero = (int) 0;

            $values = [$rand_user, Func::tokenGenerator(), $post['title'], $post['desc'], $post['category'], $zero, $zero, Func::dateFormat(), time()];

            // Proceeded to insert the data into the data base
            $inserting = new Insert($db, "post", $subject, "");
            $action = $inserting->push($values, 'issssiisi');
            if(!$action) return $action;

            $i++;
        endif;

        // Turning on the database when the whole process is complete and error free
        //$db->autocommit(TRUE);
    }


    

    /*file_put_contents('post.json', json_encode($setup));*/


    /**
     * https://www.song-lyrics-generator.org.uk/    SOME RAPS
     * https://www.lyrics.cat/   SOME RAPS
     * https://examples.yourdictionary.com/    MOST POEMS
     * http://gistandrhymes.com/   SOME RAPS
     * https://unusedlyricsfree.wordpress.com/  WHERE I GOT MOST OF MY SONGS FROM
     * http://www.laughfactory.com/  WHERE I GO MY JOKES FROM
    */
?>