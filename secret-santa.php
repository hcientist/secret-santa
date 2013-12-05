<?php
// enter your friends' names and emails here
$contacts = array(
    'name' => 'theiremail@domain.tld', 
    'name1' => 'theiremail@domain.tld',
    'name2' => 'theiremail@domain.tld',
    'name3' => 'theiremail@domain.tld',
    'name4' => 'theiremail@domain.tld',
    'name5' => 'theiremail@domain.tld',
    'name6' => 'theiremail@domain.tld',
    'name7' => 'theiremail@domain.tld',
    'name8' => 'theiremail@domain.tld',
    'name9' => 'theiremail@domain.tld',
    'name0' => 'theiremail@domain.tld',
    'name10' => 'theiremail@domain.tld'
);

// groups are currently symmetrical. anyone in a group will NOT get anyone else in that group
// a person may appear in multiple groups
// so this is where you might indicate what people should not be assigned to each other
$groups = array(
    array('name', 'name1'),
    array('name2', 'name3'),
    array('name4', 'name5'),
    array('name6', 'name7', 'name8', 'name9'),
    array('name0', 'name10')
);


// // // // // // // // // // // // // // // // // // // // // // // // 
// should not have to edit past this point
// // // // // // // // // // // // // // // // // // // // // // // // 

function pairOK($santa, $receiver, $groups) {
    foreach ($groups as $group) {
        if (in_array($santa, $group) && in_array($receiver, $group)) {
            return FALSE;
        }
    }
    return TRUE;
}

function makeAssignments($contacts, $groups) {
    $assignments = array();

    $attempt = 0;

    while (count($assignments) < count($contacts)) {
        $attempt++;
        $santa = array_keys($contacts)[rand(0,count($contacts)-1)];
        while (array_key_exists($santa, $assignments)) {
            $attempt++;
            $santa = array_keys($contacts)[rand(0,count($contacts)-1)];
        }
        $receiver = array_keys($contacts)[rand(0,count($contacts)-1)];
        $attempt++;
        while (in_array($receiver, $assignments) || strcmp($santa, $receiver)==0 || !pairOK($santa, $receiver, $groups)) {
            $attempt++;
            $receiver = array_keys($contacts)[rand(0,count($contacts)-1)];            
        }
        $assignments[$santa]=$receiver;
    }
    return $assignments;
}

function notifyFolks($contacts, $assignments) {
    foreach ($assignments as $santa => $receiver) {
        $message = "Hey ".$santa.", \nYour Secret Santa designee is ".$receiver.". \nCheerfully, \nSecret Santa\n";
        // echo $message;
        // echo $contacts[$santa];
        mail($contacts[$santa], '[Secret Santa] your secret assignment is enclosed', $message);
    }
}

function verifyAssignments($assignments, $groups, $contacts) {
    foreach ($assignments as $santa => $receiver) {
        if (!pairOK($santa, $receiver, $groups)) {
            echo "bad:";
            echo $santa;
            echo $receiver;
            echo "\n";
            return;
        }
        else {
            echo "OK:";
            echo $santa;
            echo $receiver;
            echo "\n";
        }
    }
    notifyFolks($contacts, $assignments);
}

$assignments = makeAssignments($contacts, $groups);
verifyAssignments($assignments, $groups, $contacts);
?>
