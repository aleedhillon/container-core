<div class="container">
    <h1 class="display-1">Files</h1>

    <div class="row">
        <?php foreach ($files as $file) : ?>
            <div class="col-md-4">

                <div class="card mt-5">
                    <img class="card-img-top" src="<?= storage_path($file['path']) ?>" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title"><?= $file['name'] ?></h5>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>