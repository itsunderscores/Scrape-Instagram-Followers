<?php
$username = $_GET['username']; //IG Username
$delay    = $_GET['delay']; //Sleep per request
$grab = $_GET['grab']; //How many accounts to grab per IG Request
if ($username == null) { echo 'enter a username'; die(); }
if($delay == null) { $delay = "3"; }
if($grab == null) { $grab = "100"; }

//Parsing Cookies
$file     = "cookies.txt";
$contents = file_get_contents($file);
$lines    = explode("\n", $contents);
foreach ($lines as $word) {
    $word      = str_replace("\n", "", $word);
    $headers[] = $word;
}

//Log accounts to this file
$file = "$username.txt";
unlink($file);
unlink("next_id.txt");

$f = fopen($file, 'a');

//Grab Account Data
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/'.$username.'/');
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$rt   = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);


//Grabbing Follower Amount
$first_step = explode('"edge_followed_by":{"count":' , $rt);
$second_step = explode('}' , $first_step[1] );
$followers1 = $second_step[0];

//Grabbing UserID
$grab_userid = explode( 'logging_page_id":"profilePage_' , $rt);
$grab_userid2 = explode('"' , $grab_userid[1] );
$user_id = $grab_userid2[0];

if($rt == null) {
    echo "Getting follower request was blank, check your account/cookies"; exit();
} else {
    if($followers1 == null) {
        echo "Follower value is null, check your account/cookies!"; exit();
    }
}

echo "UserID: $user_id - Followers: $followers1<br>";

$number    = "12";
if($followers1 <= 12) {
    $followers = 1;
} else {
    $followers = $followers1 / $grab;
}

echo "File will be saved: <a target='_blank' href='$file.txt'>$file</a>";
for ($x = 1; $x <= $followers; $x++) {
    
    echo "Request: $x / $followers<br>";
    
    //Building Syntax
    $id = file_get_contents('next_id.txt');
    if ($number == "12") {
        $syntax = $grab;
    } else {
        $syntax = "$grab&max_id=$id";
    }
    

    //Request to IG
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://i.instagram.com/api/v1/friendships/'.$user_id.'/followers/?count=' . $syntax . '&search_surface=follow_list_page');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $rt   = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    
    if($rt == null) {
        echo "Something went wrong grabbing the list...<br>";
    } else {
    
    $data   = json_decode($rt);
    $ipList = array();
    foreach ($data->users as $entry) {
        $list_username = $entry->username;
        if ($list_username != null) {
            //echo "$list_username<br>";
            
            fwrite($f, "$list_username\n");
        }
    }
    
    if($followers <= 12) { } else {
        //Grabbing new ID for next list
        $grab_json   = json_decode($rt, true);
        $next_max_id = $grab_json['next_max_id'];
        if($next_max_id == null) {
            echo "Something went wrong grabbing next_max_id, most likely limited/locked... Delaying 120 seconds, check your account!<br>"; sleep(120);
        } else {
        file_put_contents('next_id.txt', $next_max_id);
    }

    $number++;
    $number++;
    $number++;
    $number++;
    $number++;
    $number++;
    $number++;
    $number++;
    $number++;
    $number++;
    $number++;
    $number++;
    sleep($delay);
} 
}
}

echo "Finished!";

fclose($f);
