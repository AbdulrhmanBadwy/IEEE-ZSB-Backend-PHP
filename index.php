<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo</title>
</head>

<body>
    <h1>Recommended Books </h1>
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

    ?>

    <ul>
        <?php foreach ($filteredBooks as $book): ?>

            <li>
                <a href="<?= $book['purchaseUrl'] ?>">
                    <?= $book['name']; ?> (<?= $book['releaseYear'] ?> ) - <?= $book['author']?>
                </a>
            </li>
        <?php endforeach ?>
    </ul>
</body>

</html>