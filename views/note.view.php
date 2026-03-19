<?php require 'partials/head.php' ?>
<?php require 'partials/nav.php' ?>

<?php require "partials/banner.php" ?>


<main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 text-white font-bold">
        <p class=" text-blue-500 underline mb-6">
            <a href="/notes">Go Back...  </a>
        </p>
            <p><?= $note['body'] ?></p>
    </div>
</main>

<?php require 'partials/footer.php' ?>