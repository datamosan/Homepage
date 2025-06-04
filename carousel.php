<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .carousel-item img {
            width: 100vw;
            height: 500px;
            object-fit: cover;
        }

        .carousel-item::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #1d4b53;
            opacity: 45%;
            z-index: 1;
        }

        .carousel-indicators [data-bs-target] {
            border-radius: 50%;
            width: 8px;
            height: 8px;
            margin: 4px;
        }
    </style>
</head>

<body>

    <?php
    require_once "connection.php";
    $carouselImages = [];
    $sql = "SELECT ContentDescription FROM decadhen.indexcontents WHERE ContentName LIKE 'Carousel%' ORDER BY ContentName ASC";
    $res = sqlsrv_query($conn, $sql);

    if ($res === false) {
        die(print_r(sqlsrv_errors(), true)); // Show the real SQL error
    }

    while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
        $carouselImages[] = $row['ContentDescription'];
    }
    ?>
    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php foreach ($carouselImages as $idx => $img): ?>
                <button type="button" data-bs-target="#carouselExampleFade" data-bs-slide-to="<?php echo $idx; ?>" class="<?php echo $idx === 0 ? 'active' : ''; ?>" aria-current="<?php echo $idx === 0 ? 'true' : 'false'; ?>" aria-label="Slide <?php echo $idx+1; ?>"></button>
            <?php endforeach; ?>
        </div>
        <div class="carousel-inner">
            <?php foreach ($carouselImages as $idx => $img): ?>
                <div class="carousel-item <?php echo $idx === 0 ? 'active' : ''; ?>">
                    <img src="<?php echo htmlspecialchars($img); ?>" class="d-block w-100" alt="...">
                </div>
            <?php endforeach; ?>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>