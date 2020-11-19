<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text Search NFA</title>

    <?php include "headtags.html"; ?>
</head>

<body>

    <div class="jumbotron text-center mt-5 mb-0" style="background:white">
        <h6 class="text-danger mb-n3">Find Me</h6>
    </div>



    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <form action="cari.php" method="POST">
                    <div class="input-group mb-4">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fa fa-search" aria-hidden="true"></i></div>
                        </div>
                        <input type="text" class="form-control" id="keyword" name="keyword" placeholder="Keyword" autocomplete="off">
                    </div>
                    <div class="input-group mb-4">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fa fa-database" aria-hidden="true"></i></div>
                        </div>
                        <select name="range" id="range" class="form-control">
                            <option value="" disabled selected>Database Ranges</option>
                            <option value=1>1-50</option>
                            <option value=2>51-100</option>
                            <option value=3>101-150</option>
                            <option value=4>151-200</option>
                            <option value=5>201-230</option>
                            <option value=6>All Articles</option>
                        </select>
                    </div>
                    <div class="form-group mt-3">
                        <div class="col-md-8 offset-md-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" id="cari" class="form-control btn btn-danger rounded-pill" name="cari">Search</button>
                                </div>
                                <div class="col-md-6">
                                    <a class="form-control btn btn-danger rounded-pill text-light" href="tambah-berita.php">Add Article</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="assets/js/bootstrap.min.js"></script>

</body>

</html>