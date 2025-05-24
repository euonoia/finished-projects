<?php
include("connect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
     

    <h2>Manage Parking</h2>
    <form method="post">
        <label for="action">Action:</label>
        <select name="action" id="action">
            <option value="park">Park Vehicle</option>
            <option value="unpark">Unpark Vehicle</option>
        </select>

        <label for="slot_id">Slot ID:</label>
        <select name="slot_id" id="slot_id">
            <?php
            foreach ($slots as $slot) {
                echo '<option value="' . htmlspecialchars($slot->getSlotId()) . '">' . htmlspecialchars($slot->getSlotId()) . '</option>';
            }
            ?>
        </select>

        <label for="plate">License Plate (for Parking):</label>
        <input type="text" name="plate" id="plate">

        <button type="submit">Submit</button>
    </form>

    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

</body>
</html>