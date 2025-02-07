<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <!-- Crucial for responsive behavior -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <?php
        session_start();
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header("Location: login.php");
            exit();
        }
        ?>
        <h1>Product List</h1>
        <a href="logout.php" class="btn btn-danger">Logout</a>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
            Add Product
        </button>

        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="index.php" id="addProductForm">
                            <div class="mb-3">
                                <label for="orderId" class="form-label">Order ID:</label>
                                <input type="text" class="form-control" id="orderId" name="orderId" required>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="productName" class="form-label">Product Name:</label>
                                    <input type="text" class="form-control" id="productName" name="productName" required>
                                </div>
                                <div class="col">
                                    <label for="customerName" class="form-label">Customer Name:</label>
                                    <input type="text" class="form-control" id="customerName" name="customerName" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="productPrice" class="form-label">Product Price:</label>
                                    <input type="number" step="0.01" class="form-control" id="productPrice" name="productPrice" required>
                                </div>
                                <div class="col">
                                    <label for="transportPrice" class="form-label">Transport Price:</label>
                                    <input type="number" step="0.01" class="form-control" id="transportPrice" name="transportPrice" required>
                                </div>
                                <div class="col">
                                    <label for="senditPrice" class="form-label">Sendit Price:</label>
                                    <input type="number" step="0.01" class="form-control" id="senditPrice" name="senditPrice" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="taxCost" class="form-label">Tax Cost:</label>
                                    <input type="number" step="0.01" class="form-control" id="taxCost" name="taxCost" required>
                                </div>
                                <div class="col">
                                    <label for="profit" class="form-label">Profit %:</label>
                                    <input type="number" step="0.01" class="form-control" id="profit" name="profit" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="orderDate" class="form-label">Order Date:</label>
                                <input type="date" class="form-control" id="orderDate" name="orderDate" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="addProductForm" class="btn btn-primary">Add Product</button>
                    </div>
                </div>
            </div>
        </div>

        <ul class="nav nav-pills mb-3 mt-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-awaiting-tab" data-bs-toggle="pill" data-bs-target="#pills-awaiting" type="button" role="tab" aria-controls="pills-awaiting" aria-selected="true">Products Awaiting Arrival</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-confirmed-tab" data-bs-toggle="pill" data-bs-target="#pills-confirmed" type="button" role="tab" aria-controls="pills-confirmed" aria-selected="false">Products with Confirmed Arrival</button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-awaiting" role="tabpanel" aria-labelledby="pills-awaiting-tab" tabindex="0">
                <h2>Products Awaiting Arrival</h2>
                <!-- Add table-responsive class -->
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Product Name</th>
                                <th>Customer Name</th>
                                <th>Product Price</th>
                                <th>Transport Price</th>
                                <th>Sendit Price</th>
                                <th>Order Date</th>
                                <th>Tax Cost</th>
                                <th>Profit %</th>
                                <th>Total Price</th>
                                <th>Action</th>
                                <th>Sendit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'mysqlconfig.php';

                            $sql = "SELECT id, orderId, productName, customerName, productPrice, transportPrice, senditPrice, orderDate, arrivalDate, taxCost, profit FROM products";
                            $result = $conn->query($sql);

                            $awaitingArrival = '';
                            $confirmedArrival = '';
                            $totalAwaitingPrice = 0;
                            $totalConfirmedPrice = 0;

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    $totalPrice = $row["productPrice"] + $row["transportPrice"] + $row["senditPrice"] + $row["taxCost"] + ($row["productPrice"] * ($row["profit"] / 100));
                                    $totalPriceFormatted = htmlspecialchars(number_format($totalPrice, 2));

                                    $orderId = htmlspecialchars($row["orderId"]);
                                    $productName = htmlspecialchars($row["productName"]);
                                    $customerName = htmlspecialchars($row["customerName"]);
                                    $productPrice = htmlspecialchars($row["productPrice"]);
                                    $transportPrice = htmlspecialchars($row["transportPrice"]);
                                    $senditPrice = htmlspecialchars($row["senditPrice"]);
                                    $orderDate = htmlspecialchars($row["orderDate"]);
                                    $taxCost = htmlspecialchars($row["taxCost"]);
                                    $profit = htmlspecialchars($row["profit"]);
                                    $arrivalDate = htmlspecialchars($row["arrivalDate"]);
                                    $deleteButton = '<a href="delete_product.php?id=' . $row["id"] . '" class="btn btn-sm btn-danger">Delete</a>';

                                    if (empty($row["arrivalDate"])) {
                                        $confirmArrivalButton = '<a href="update_arrival.php?id=' . $row["id"] . '" class="btn btn-sm btn-success">Confirm Arrival</a>';
                                        $senditButton = '<button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#updateSenditModal" data-bs-id="' . $row["id"] . '">Sendit</button>';
                                        $awaitingArrival .= "<tr>
                                            <td>$orderId</td>
                                            <td>$productName</td>
                                            <td>$customerName</td>
                                            <td>$productPrice</td>
                                            <td>$transportPrice</td>
                                            <td>$senditPrice</td>
                                            <td>$orderDate</td>
                                            <td>$taxCost</td>
                                            <td>$profit</td>
                                            <td>$totalPriceFormatted</td>
                                            <td>$confirmArrivalButton</td>
                                            <td>$senditButton</td>
                                            <td>$deleteButton</td>

                                        </tr>";
                                        $totalAwaitingPrice += $row["productPrice"] + $row["transportPrice"] + $row["senditPrice"] + $row["taxCost"] + ($row["productPrice"] * ($row["profit"] / 100));
                                    } else {
                                        $confirmedArrival .= "<tr>
                                            <td>$orderId</td>
                                            <td>$productName</td>
                                            <td>$customerName</td>
                                            <td>$productPrice</td>
                                            <td>$transportPrice</td>
                                            <td>$senditPrice</td>
                                            <td>$orderDate</td>
                                            <td>$arrivalDate</td>
                                            <td>$taxCost</td>
                                            <td>$profit</td>
                                            <td>$totalPriceFormatted</td>
                                            <td>$deleteButton</td>
                                        </tr>";
                                         $totalConfirmedPrice += $row["productPrice"] + $row["transportPrice"] + $row["senditPrice"] + $row["taxCost"] + ($row["productPrice"] * ($row["profit"] / 100));
                                    }
                                }
                                echo $awaitingArrival;
                            } else {
                                echo '<tr><td colspan="13">No products found</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

             <!-- Update Sendit Modal -->
            <div class="modal fade" id="updateSenditModal" tabindex="-1" aria-labelledby="updateSenditModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateSenditModalLabel">Update Sendit Price</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="update_sendit.php" id="updateSenditForm">
                                <input type="hidden" name="id" id="productId">
                                <div class="mb-3">
                                    <label for="senditPrice" class="form-label">Sendit Price:</label>
                                    <input type="number" step="0.01" class="form-control" id="senditPrice" name="senditPrice" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" form="updateSenditForm" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="pills-confirmed" role="tabpanel" aria-labelledby="pills-confirmed-tab" tabindex="0">
                <h2>Products with Confirmed Arrival</h2>
                <!-- Add table-responsive class -->
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Product Name</th>
                                <th>Customer Name</th>
                                <th>Product Price</th>
                                <th>Transport Price</th>
                                <th>Sendit Price</th>
                                <th>Order Date</th>
                                <th>Arrival Date</th>
                                <th>Tax Cost</th>
                                <th>Profit %</th>
                                <th>Total Price</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                echo $confirmedArrival;
                                $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <h2>Statistics</h2>
            <p>Total Price of Products Awaiting Arrival: <?php echo htmlspecialchars(number_format($totalAwaitingPrice, 2)); ?></p>
            <p>Total Price of Products with Confirmed Arrival: <?php echo htmlspecialchars(number_format($totalConfirmedPrice, 2)); ?></p>
        </div>
    </div>

    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    if (isset($_SESSION['error_message'])) {
                        echo $_SESSION['error_message'];
                        unset($_SESSION['error_message']);
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php
            if (isset($_SESSION['error_message'])) {
                echo 'var errorModal = new bootstrap.Modal(document.getElementById("errorModal"));';
                echo 'errorModal.show();';
            }
            ?>

            var updateSenditModal = document.getElementById('updateSenditModal');
            if (updateSenditModal) {
                updateSenditModal.addEventListener('show.bs.modal', function (event) {
                  // Button that triggered the modal
                  var button = event.relatedTarget;
                  // Extract info from data-bs-* attributes
                  var productId = button.getAttribute('data-bs-id');

                  // If necessary, you could initiate an AJAX request here
                  // and then do the updating in a callback.

                  // Update the modal's content.
                  var modalTitle = updateSenditModal.querySelector('.modal-title');
                  var modalBodyInput = updateSenditModal.querySelector('#productId');

                  modalTitle.textContent = 'Update Sendit Price for Product ID ' + productId;
                  modalBodyInput.value = productId;
                });
            }
        });
    </script>
</body>
</html>
<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'mysqlconfig.php';

    $orderId = $_POST["orderId"];
    $productName = $_POST["productName"];
    $customerName = $_POST["customerName"];
    $productPrice = $_POST["productPrice"];
    $transportPrice = $_POST["transportPrice"];
    $senditPrice = $_POST["senditPrice"];
    $orderDate = $_POST["orderDate"];
    $taxCost = $_POST["taxCost"];
    $profit = $_POST["profit"];

    $sql = "INSERT INTO products (orderId, productName, customerName, productPrice, transportPrice, senditPrice, orderDate, taxCost, profit)
            VALUES ('$orderId', '$productName', '$customerName', $productPrice, $transportPrice, '$senditPrice', '$orderDate', $taxCost, $profit)";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $sql . "<br>" . $conn->error;
        header("Location: index.php");
        exit();
    }

    $conn->close();
}

echo "";
?>

