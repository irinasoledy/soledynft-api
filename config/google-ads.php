<?php
return [
    //Environment=> test/production
    'env' => 'test',
    //Google Ads
    'production' => [
        'developerToken' => "YOUR-DEV-TOKEN",
        'clientCustomerId' => "123545481940-4mdaf48bm17k5bteu5agvn9hu93rikkj.apps.googleusercontent.com",
        'userAgent' => "YOUR-NAME",
        'clientId' => "CLIENT-ID",
        'clientSecret' => "CLIENT-SECRET",
        'refreshToken' => "REFRESH-TOKEN"
    ],
    'test' => [
        'developerToken' => "HGnirrdw3jWZLYhC48Tl5g",
        'clientCustomerId' => "940-653-7294",
        'userAgent' => "AP-ads-api",
        'clientId' => "123545481940-4mdaf48bm17k5bteu5agvn9hu93rikkj.apps.googleusercontent.com",
        'clientSecret' => "VhUXtjChcGC3Ogz1xQjWXf_3",
        'refreshToken' => "1//096yXDMsnzqYaCgYIARAAGAkSNwF-L9IrgMpQqFm3BFwo2NGmlOMGrtyPtjp64nOxiLhR2C9K6YjkajEKmmRPQ_t8feST0m7dGUA"
    ],
    'oAuth2' => [
        'authorizationUri' => 'https://accounts.google.com/o/oauth2/v2/auth',
        'redirectUri' => 'urn:ietf:wg:oauth:2.0:oob',
        'tokenCredentialUri' => 'https://www.googleapis.com/oauth2/v4/token',
        'scope' => 'https://www.googleapis.com/auth/adwords'
    ]
];
