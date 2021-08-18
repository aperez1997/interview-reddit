<?php

// finding largest viewcount by platform

$url = 'https://codesignal.s3.amazonaws.com/uploads/1612909377814/page_views.json';
$contents = file_get_contents($url);
$decode = json_decode($contents, true);

$result = getHighest7Day($decode);
print_r($result);

function getHighest7Day(array $structure)
{
    // keep track of largest 7 day window
    $largestWindow = [];
    $largestCount = 0;
    $result = [];

    // loop over days
    foreach ($structure as $day => $dayData){

        // initially, build largest window and do no comparisons
        if (count($largestWindow) < 7){
            // once we have a largest 7-day window, still continue
            $largestWindow[] = $dayData;

            $result = getLargestCountInWindow($largestWindow);
            $largestCount = $result[1];
            continue;
        }

        // once we have a new 7-day window, start comparing and keep largest
        $newWindow = array_slice($largestWindow, 1);
        $newWindow[] = $dayData;

        $newWindowResult = getLargestCountInWindow($newWindow);
        $newWindowCount = $result[1];
        if ($newWindowCount > $largestCount){
            $result = $newWindowResult;
            $largestCount = $newWindowCount;
        }
    }

    return $result;
}

function getLargestCountInWindow(array $dayData) : array
{
    $counts = [];
    // build total counts by platform
    foreach ($dayData as $platform => $platformData){
        $pageviews = $platformData['pageviews'];

        // if there is a count, add to it
        if (array_key_exists($platform, $counts)){
            $counts[$platform] += $pageviews;
        } else {
            // write a new key in count if there is none
            $counts[$platform] = $pageviews;
        }
    }

    // loop over counts, return largest
    $result = [];
    $largest = 0;
    foreach ($counts as $platform => $count){
        if ($count > $largest){
            $largest = $count;
            $result = [$platform, $count];
        }
    }
    return $result;
}


$result = getHighest7Day($decode);
print_r($result);




/*
$day = '2020-12-16';
$result = getHighestPlatformCountByDay($decode, $day);
print_r($result);
*/




function getHighestPlatformCountByDay(array $structure, string $wantedDay) : array
{
    // loop over the days for the wanted day
    $result = [];
    foreach ($structure as $day => $dayData){
        if ($day != $wantedDay){
            continue;
        }

        // loop over platform info (platform => data)
        $largest = 0;
        foreach ($dayData as $platform => $platformData){
            $pageviews = $platformData['pageviews'];
            // - find largest count
            if ($pageviews > $largest){
                $largest = $pageviews;
                $result = [$platform => $platformData];
            }
        }
    }

    return $result;
}
/**
 * Codewriting

A subreddit moderator would like to know some information about how many users are seeing their subreddit. We have some subreddit view data that looks like this:

{
"2020-06-04": {
"android": {
"pageviews": 1048749,
"uniques": 283306
},
"ios": {
"pageviews": 828792,
"uniques": 390098
},
"mobile web": {
"pageviews": 184459,
"uniques": 115771
},
"new reddit": {
"pageviews": 398615,
"uniques": 233145
},
"old reddit": {
"pageviews": 907138,
"uniques": 196448
}
},
"2020-06-05": {
"android": {
"pageviews": 818019,
"uniques": 202770
},
"ios": {
"pageviews": 543987,
"uniques": 241062
},
"mobile web": {
"pageviews": 168518,
"uniques": 109986
},
"new reddit": {
"pageviews": 196908,
"uniques": 109611
},
"old reddit": {
"pageviews": 660134,
"uniques": 123657
}
}
}
Your program can download the source here.

Step 1
Please write a function that accepts a given day, and returns the platform name and count for the platform with the highest views.

Step 2
Write a function that determines which platform had the most views over a 7 day period. The function should return the name and total page count of the most popular platform.

Step 3
How would you scale a system so that it can calculate page views for millions of subreddits?

[execution time limit] 4 seconds (php)
 */