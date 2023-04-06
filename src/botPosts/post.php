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
    $selecting = new Select($connect);
    $selecting->more_details("");
    $selecting->process();
    $values = $selecting->pull("id", "register");
    $selecting->reset();

    if($values[1] > 0) {
        $id = $values[0];
    }

    $min_id = min($values[0]);
    $max_id = max($values[0]);

    $connect->autocommit(FALSE);
    for($i = 0; $i < $post_len;) {
        $rand_post = random_int(0, 45);

        $subject = ["user", "token", "title", "content", "category", "comments_blocked", "privacy", "date", "time"];

        if(!in_array($rand_post, $exist)){
            $post = json_decode(json_encode($posts[$rand_post]), true);
            array_push($exist, $rand_post);

            $rand_id = random_int($min_id['id'], $max_id['id']);
            $rand = bin2hex(random_bytes(64));
            $title = $post['title'];
            $composed = $post['desc'];
            $genre = $post['category'];
            $block_digits = (int) 0;
            $digit = (int) 0;
            $date = Date("d/m/y h:i:sa");
            $time = time();

            $values = [$rand_id, $rand, $title, $composed, $genre, $block_digits, $digit, $date, $time];

            $inserting = new Insert($connect, "home_page", $subject, "");
            $inserting->create_ques();
            $inserting->push($values, 'issssiisi');
            $inserting->reset();

            $i++;
        }
        //$connect->autocommit(TRUE);
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