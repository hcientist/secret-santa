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

$auditor = "your.kind.friend.who.isnt.participating@domain.tld";

$spendinglimit = "$25.00";
$year = date("Y");

// groups are currently symmetrical. anyone in a group will NOT get anyone else in that group
// a person may appear in multiple groups
// so this is where you might indicate what people should not be assigned to each other
$groups = array(
    array('name', 'name1'),
    array('name2', 'name3'),
    array('name4', 'name5'),
    array('name6', 'name7', 'name8', 'name9') //not all names have to be in a group.
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
        $contactKeys = array_keys($contacts);
        $santa = $contactKeys[rand(0,count($contacts)-1)];
        while (array_key_exists($santa, $assignments)) {
            $attempt++;
            $santa = $contactKeys[rand(0,count($contacts)-1)];
        }
        $receiver = $contactKeys[rand(0,count($contacts)-1)];
        $attempt++;
        while (in_array($receiver, $assignments) || strcmp($santa, $receiver)==0 || !pairOK($santa, $receiver, $groups)) {
            $attempt++;
            $receiver = $contactKeys[rand(0,count($contacts)-1)];            
        }
        $assignments[$santa]=$receiver;
    }
    return $assignments;
}

function notifyFolks($contacts, $assignments) {
    foreach ($assignments as $santa => $receiver) {
        $message = "Hey ".$santa.", \nYour Secret Santa designee is ".$receiver.". \nThe spending limit is ".$spendinglimit."\n\nCheerfully, \nSecret Santa\n";
        // echo $message;
        // echo $contacts[$santa];
        mail($contacts[$santa], "[Secret Santa 2015] your secret assignment is enclosed", $message);
    }
}

function verifyAssignments($assignments, $groups, $contacts) {
    $all_assignments_for_auditor = "One of your friends has designated you as the auditor for their secret santa distribution. Please take a quick look below and ensure that no one was assigned themselves, and that no one was assigned another member of their own group.\n\nGroups/Households on the following lines should not have been matched to each other.\nGroups:\n";
    foreach ($groups as $group) {
        $all_assignments_for_auditor = $all_assignments_for_auditor.implode(",", $group)."\n";
    }
    $all_assignments_for_auditor = $all_assignments_for_auditor."\n====================\nMatches:\n";
    foreach ($assignments as $santa => $receiver) {
        if (!pairOK($santa, $receiver, $groups)) {
            echo "bad:";
            // echo $santa;
            // echo $receiver;
            // echo "\n";
            return;
        }
        else {
            // echo "OK:";
            // echo $santa;
            // echo $receiver;
            // echo "\n";
            $all_assignments_for_auditor = $all_assignments_for_auditor.$santa."=>".$receiver."\n";
        }
    }
    // echo "\n\n";
    notifyFolks($contacts, $assignments);
    // echo $all_assignments_for_auditor;
    mail($auditor, "thanks for helping with the ".$year." secret santa distribution", $all_assignments_for_auditor);
}

$assignments = makeAssignments($contacts, $groups);
verifyAssignments($assignments, $groups, $contacts);
?>
