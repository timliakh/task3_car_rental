<?php

require_once 'backend/sdbh.php';
$dbh = new sdbh();

$total_cost = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $product_id = $_POST['product'];
    $days = $_POST['customRange1'];
    $selected_services = isset($_POST['services']) ? $_POST['services'] : [];

    $total_cost = $dbh->calculate_rent_cost($product_id, $days, $selected_services);

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Прокат</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="assets/css/style.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"  crossorigin="anonymous"></script>
    <style>
        .container {
            margin-top: 50px;
            border-radius: 15px;
            border: 3px solid #333;
        }
        .col-3 {
            border-radius: 13px 0 0 13px;
            display: flex;
            align-items: center;
            flex-flow: column;
            justify-content: center;
            font-size: 26px;
            font-weight: 900;
        }
        label:not([class="form-check-label"]) {
            font-size: 16px;
            font-weight: 600;
        }
        .form-check-input:checked {
            background-color: #b035ed;
            border-color: #b035ed;
        }
        .col-9 {
            padding: 25px;
        }
        .btn-primary, .btn-primary:hover {
            color: #fff;
            background-color: #b035ed;
            border-color: #b035ed;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row row-body">
        <div class="col-3">
            <img src="assets/img/logo.png" alt="logo" style="max-height:50px"/>
            <h1>Прокат</h1>
        </div>
        <div class="col-9">
            <form action="index.php" method="post" id="form">
                <label class="form-label" for="product">Выберите продукт:</label>
                <select class="form-select" name="product" id="product">
                    <?php

                    $products = $dbh->mselect_rows('a25_products', NULL, 0, 3, 'id', 'ASC');

                    foreach ($products as $product) {

                        echo '<option value="' . $product['ID'] . '">' . $product['NAME'] . ' за ' . $product['PRICE'] . '</option>';

                    }

                    ?>
                </select>
                <label for="customRange1" class="form-label">Количество дней:</label>
                <input type="number" class="form-control" id="customRange1" name="customRange1" min="1" max="30" required>
                <label for="customRange1" class="form-label">Дополнительно:</label>
                <?php

                $services = unserialize($dbh->mselect_rows('a25_settings', ['set_key' => 'services'], 0, 1, 'id')[0]['set_value']);

                foreach ($services as $k => $s) {
                    echo '<div class="form-check">
                    <input class="form-check-input" type="checkbox" name="services[]" value="' . $k . '" id="' . $k . '" checked>
                    <label class="form-check-label" for="' . $k . '">' . $k . ': ' . $s . '</label>
                    </div>';
                }

                ?>
                <button type="submit" class="btn btn-primary">Рассчитать</button>
            </form>
        </div>
    </div>
    <?php if ($total_cost !== ''): ?>
        <div class="row row-form">
            <div class="col-12">
                <h4>Итоговая стоимость:</h4>
                <p><?php echo $total_cost; ?></p>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>