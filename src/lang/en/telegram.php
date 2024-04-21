<?php

declare(strict_types=1);

return [
    'middleware' => [
        'access_denied' => 'You cannot use this bot.',
    ],

    'commands' => [
        'start' => [
            'welcome_message' => "You are welcome to AniLibrary Bot!\nPlease, select the desired action below:",
        ],

        'random_anime' => [
            'unable_to_find_anime' => 'Unable to find any anime.',
        ],
    ],

    'conversations' => [
        'add_anime' => [
            'provide_url'      => 'Provide url for scraping, this may take a few minutes.',
            'scrape_failed'    => 'Could not scrape anime, try again.',
            'scrape_has_ended' => 'Scraping has ended.',
            'view_anime'       => 'View anime.',
        ],

        'search_anime' => [
            'example'    => "Provide anime title, genres, voice actors and more, for example :\nNaruto, Adventure, AniDub\nand the bot will try to find something for you.",
            'no_results' => 'No available results. Please try again.',
        ],
    ],

    'callbacks' => [
        'anime_list' => [
            'render_error' => 'Could not render anime list.',
        ],

        'anime_search' => [
            'render_error' => 'Could not render search results.',
        ],

        'view_anime' => [
            'render_error' => 'Could not get anime.',
        ],
    ],

];
