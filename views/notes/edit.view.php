<?php require base_path('views/partials/head.php') ?>
<?php require base_path('views/partials/nav.php') ?>
<?php require base_path("views/partials/banner.php") ?>


<main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 text-white font-bold">
        <form method="post" action="/note">
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="id" value="<?= $note['id']?>">
            <div class="space-y-12">
                <div class="border-b border-white/10 pb-12">

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                        <div class="col-span-full">
                            <label for="body" class="block text-sm/6 font-medium text-white">Body</label>
                            <div class="mt-2">
                                <textarea id="body" name="body" rows="3"
                                    class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                                    placeholder="Here's an idea for a note..."><?= $note['body'] ?></textarea>
                                <?php if (isset($errors['body'])): ?>
                                    <p class="text-red-500 text-xs mt-2">
                                        <?= $errors['body'] ?>
                                    </p>
                                <?php endif ?>

                            </div>
                        </div>
                    </div>

                    <div class=" px-4 py-3 text-right sm:px-6 flex gap-x-4 justify-end">
                        <a
                            href="/notes"
                            class="rounded-md bg-gray-500 px-3 py-2 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                            >
                            Cancel
                        </a>
                    <button type="submit"
                        class="rounded-md bg-indigo-500 px-3 py-2 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                        Update </button>
                </div>
        </form>

    </div>
</main>

<?php require base_path('views/partials/footer.php') ?>