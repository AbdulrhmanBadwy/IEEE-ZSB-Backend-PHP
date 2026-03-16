<?php
    $books = [
        [
            "name" => "Do Androids Dream of Electric SheeP",
            "author" => "Philip K. Dick",
            'releaseYear' => 1968,

            "purchaseUrl" => "http://emxmaple.com",
        ],
        [
            "name" => 'Project Hali Mary',
            "author" => 'Andy Weir',
            'releaseYear' => 2011,

            "purchaseUrl" => 'http://example.com',
        ],
        [
            'name' => 'The Martian',
            'author' => 'Andy Weir',
            'releaseYear' => 2011,
            'purchaseUrl' => 'http://example.com',
        ]

    ];
    function filter($items,$function) 
    {
        $filteredItems = [];

        foreach ($items as $item) { 
            if($function($item)){
                array_push($filteredItems, $item);
            }
        }
        return $filteredItems;

    }

    $filteredBooks = array_filter($books , function($book){
        return $book['author'] == 'Andy Weir'; 
    } );


    require "inedex.view.php";