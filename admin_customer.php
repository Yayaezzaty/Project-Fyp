<?php
include 'db_connection.php';
include 'admin_navigation.php'; // Including the navigation

$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <title>Admin - Customer Management</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 90%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .search-bar {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .edit-btn, .delete-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .edit-btn {
            background-color: #28a745;
            color: white;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        .edit-btn:hover {
            background-color: #218838;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }

        <style>
/* Custom popup styles */
.custom-popup {
    font-family: 'Poppins', sans-serif;
    border-radius: 10px;
}

/* Custom Confirm Button */
.custom-confirm {
    background-color: #ff5e57 !important;
    color: white !important;
    font-weight: bold;
    padding: 10px 20px !important;
    border-radius: 8px !important;
}

/* Custom Cancel Button */
.custom-cancel {
    background-color: #1d9bf0 !important;
    color: white !important;
    font-weight: bold;
    padding: 10px 20px !important;
    border-radius: 8px !important;
}


    </style>
    <script>
        function searchCustomer() {
            let input = document.getElementById("search").value.toLowerCase();
            let rows = document.querySelectorAll("table tbody tr");
            rows.forEach(row => {
                let name = row.cells[0].textContent.toLowerCase();
                if (name.includes(input)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        function confirmDelete(event, form) {
    event.preventDefault(); // Prevent form from submitting immediately
    Swal.fire({
        title: "Are you sure?",
        text: "This action cannot be undone!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit(); // If confirmed, submit the form
        }
    });
}

    </script>
</head>
<body>
    <div class="container">
        <h2>Customer Management</h2>
        <input type="text" id="search" class="search-bar" placeholder="Search by name..." onkeyup="searchCustomer()">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['address']) ?></td>
                    <td>
                        <a href="edit_delete.php?id=<?= $row['user_id'] ?>" class="edit-btn">Edit</a>
                        <form action="delete_customer.php" method="POST" style="display:inline;" onsubmit="confirmDelete(event, this)">
                            <input type="hidden" name="id" value="<?= $row['user_id'] ?>">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
