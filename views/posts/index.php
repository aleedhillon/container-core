<div class="container">
    <h1 class="display-1">Posts</h1>

    <div class="row">
        <?php foreach ($posts as $post) : ?>
            <div class="col-md-4">

                <div class="card mt-5">
                    <div class="card-body">
                        <h5 class="card-title"><?= $post['title'] ?></h5>
                        <p class="card-text"><?= $post['body'] ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>