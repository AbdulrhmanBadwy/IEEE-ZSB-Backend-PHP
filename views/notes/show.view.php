<?php require base_path('views/partials/head.php') ?>
<?php require base_path('views/partials/nav.php') ?>
<?php require base_path("views/partials/banner.php" )?>


<main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 text-white font-bold">
        <p class=" text-blue-500 underline mb-6">
            <a href="/notes">Go Back... </a>
        </p>
        <p> <?= htmlspecialchars($note['body']) ?>
        </p>

        <form class="mt-6" method="post">
            <input type="hidden" name ="id" value="<?= $note['id'] ?>">
            <button class="text-sm text-red-500">
                Delete
            </button>
        </form>

    </div>
</main>

<?php require base_path('views/partials/footer.php') ?>