 <?php
$username = $_GET['username'];
$delay    = $_GET['delay'];
if ($username == null) {
    echo 'enter a username';
    die();
}

//Parsing Cookies
$file     = "cookies.txt";
$contents = file_get_contents($file);
$lines    = explode("\n", $contents);
foreach ($lines as $word) {
    $word      = str_replace("\n", "", $word);
    $headers[] = $word;
}

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
$first_step = explode( '<meta content="' , $rt);
$second_step = explode(' ' , $first_step[1] );
$followers = $second_step[0];
$followers = str_replace(",", "", $followers);

//Grabbing UserID
$grab_userid = explode( 'logging_page_id":"profilePage_' , $rt);
$grab_userid2 = explode('"' , $grab_userid[1] );
$user_id = $grab_userid2[0];

if($rt == null) {
    echo "Getting follower request was blank"; exit();
} else {
    if($followers == null) {
        echo "Follower value is null"; exit();
    }
}

$number    = "12";
$followers = $followers / 12;
for ($x = 1; $x <= $followers; $x++) {
    
    //Building Syntax
    $id = file_get_contents('next_id.txt');
    if ($number == "12") {
        $syntax = "12";
    } else {
        $syntax = "12&max_id=$id";
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
    
    //Grabbing new ID for next list
    $grab_json   = json_decode($rt, true);
    $next_max_id = $grab_json['next_max_id'];
    if($next_max_id == null) {
        echo "Something went wrong grabbing next_max_id<br>";
    } else {
    file_put_contents('next_id.txt', $next_max_id);
    
    $data   = json_decode($rt);
    $ipList = array();
    foreach ($data->users as $entry) {
        $list_username = $entry->username;
        if ($list_username != null) {
             "$list_username<br>";
        }
    }
    
    $number++; $number++; $number++; $number++; $number++; $number++; $number++; $number++; $number++; $number++; $number++; $number++;
    sleep($delay);
} 
}
}
