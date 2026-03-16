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
    function filterByAuthor($author) 
    {
        global $books;
        $filteredBooks = [];

        foreach ($books as $book) { 
            if($book['author' ] == $author){
                array_push($filteredBooks, $book);
            }
        }
        return $filteredBooks;

    }


    ?>

    <ul>
        <?php foreach (filterByAuthor('Philip K. Dick') as $book): ?>

            <li>
                <a href="<?= $book['purchaseUrl'] ?>">
                    <?= $book['name']; ?> (<?= $book['releaseYear'] ?> ) - <?= $book['author']?>
                </a>
            </li>
        <?php endforeach ?>
    </ul>
</body>

</html>