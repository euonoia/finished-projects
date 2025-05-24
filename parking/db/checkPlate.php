<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['check_plate'])) {
    $plate = filter_input(INPUT_POST, 'plate', FILTER_SANITIZE_STRING);
    $query = "SELECT * FROM customer WHERE plate = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $plate);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['id'];
        $stored_plate = $row['plate'];

        if ($plate === $stored_plate) {

          
            // Check if the plate is already assigned to a slot
            $slotCheckQuery = $conn->prepare("SELECT `slotnumber`, `assigned_time` FROM `parking_slots` WHERE `plate` = ?");
            $slotCheckQuery->bind_param("s", $stored_plate);
            $slotCheckQuery->execute();
            $slotCheckResult = $slotCheckQuery->get_result();

            if ($slotCheckResult->num_rows > 0) {
                $slotData = $slotCheckResult->fetch_assoc();
                  // Update logout_history
                $insertLogoutHistoryStmt = $conn->prepare("INSERT INTO `logout_history` (`id`, `plate`, `logout_time`) VALUES (?, ?, NOW())");
                $insertLogoutHistoryStmt->bind_param("is", $id, $stored_plate);
                $insertLogoutHistoryStmt->execute();
                $insertLogoutHistoryStmt->close();
                $assignedSlot = $slotData['slotnumber'];

                // Fetch login time from login_history
                $loginTimeQuery = $conn->prepare("SELECT `login_time` FROM `login_history` WHERE `plate` = ? ORDER BY `login_time` DESC LIMIT 1");
                $loginTimeQuery->bind_param("s", $stored_plate);
                $loginTimeQuery->execute();
                $loginTimeResult = $loginTimeQuery->get_result();
                $loginTime = $loginTimeResult->fetch_assoc()['login_time'];
                $loginTimeQuery->close();
                
                // Fetch logout time from logout_history
                $logoutTimeQuery = $conn->prepare("SELECT `logout_time` FROM `logout_history` WHERE `plate` = ? ORDER BY `logout_time` DESC LIMIT 1");
                $logoutTimeQuery->bind_param("s", $stored_plate);
                $logoutTimeQuery->execute();
                $logoutTimeResult = $logoutTimeQuery->get_result();
                $logoutTime = $logoutTimeResult->fetch_assoc()['logout_time'];
                $logoutTimeQuery->close();

                // Calculate duration
                $loginDateTime = new DateTime($loginTime);
                $logoutDateTime = new DateTime($logoutTime);
                $duration = $loginDateTime->diff($logoutDateTime);

                // Display the receipt
                echo '<div style="background-color: #f9f9f9; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); text-align: center; font-family: Arial, sans-serif; margin: 20px auto; max-width: 400px;">';
                echo '<h3 style="color: #333; font-size: 24px; margin-bottom: 10px;">Receipt</h3>';
                echo '<p style="color: #555; font-size: 16px; margin: 5px 0;">Plate: <strong>' . htmlspecialchars($stored_plate, ENT_QUOTES, 'UTF-8') . '</strong></p>';
                echo '<p style="color: #555; font-size: 16px; margin: 5px 0;">Slot: <strong>' . htmlspecialchars($assignedSlot, ENT_QUOTES, 'UTF-8') . '</strong></p>';
                echo '<p style="color: #555; font-size: 16px; margin: 5px 0;">Time in: <strong>' . htmlspecialchars($loginTime, ENT_QUOTES, 'UTF-8') . '</strong></p>';
                echo '<p style="color: #555; font-size: 16px; margin: 5px 0;">Time out: <strong>' . htmlspecialchars($logoutTime, ENT_QUOTES, 'UTF-8') . '</strong></p>';
                echo '<p style="color: #555; font-size: 16px; margin: 5px 0;">Duration: <strong>' . $duration->format('%H hours, %i minutes') . '</strong></p>';
                echo '<a href="/parking/index.php"><button style="margin-top: 10px; padding: 10px 20px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">Return</button></a>';
                echo '</div>';

                // Update the slot status to available
                $updateSlotStmt = $conn->prepare("UPDATE `parking_slots` SET `plate` = NULL, `assigned_time` = NULL, `status` = 'available' WHERE `slotnumber` = ?");
                $updateSlotStmt->bind_param("s", $assignedSlot);
                $updateSlotStmt->execute();
                $updateSlotStmt->close();

              

                $slotCheckQuery->close();
                return; // Exit to prevent further processing
            }
            $slotCheckQuery->close();

            
              
                
            $firstname = htmlspecialchars($row['firstname'], ENT_QUOTES, 'UTF-8');
            $lastname = htmlspecialchars($row['lastname'], ENT_QUOTES, 'UTF-8');
            $customerType = htmlspecialchars($row['customerType'], ENT_QUOTES, 'UTF-8');
            $subscriptionType = htmlspecialchars($row['subscriptionType'], ENT_QUOTES, 'UTF-8');
           
            switch ($subscriptionType) 
            
            
            {
                case 'regular':
               echo "
            <body 
                style=' 
            background-color:rgb(226, 120, 120);
            font-family: poppins, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0; )
            '>
            <div style=
            '
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            font-size: 18px;
            color: #333;
            margin-top: 10px;
            '>
                
                <p>Hi, $firstname $lastname!, Customer type $customerType please pay ₱20 to proceed</p>

                 
            </body>";
         
       

    // Fetch available parking slots
    $availableSlotsQuery = $conn->prepare("SELECT `slotnumber` FROM `parking_slots` WHERE `status` = 'available'");
    $availableSlotsQuery->execute();
    $availableSlotsResult = $availableSlotsQuery->get_result();

    echo '<button id="alreadyPaidButton" style="margin-top: 10px; padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">Already Paid</button>';

    echo '<div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 999;"></div>';

    echo '<div id="paidForm" style="display: none; position: fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); z-index: 1000; max-width: 400px; width: 90%;">';
    echo '<form method="POST" action="checkPlate.php" style="display: flex; flex-direction: column; gap: 15px;">';
    echo '<label for="slot" style="font-size: 16px; font-weight: bold; color: #333;">Select Available Slot:</label>';
    echo '<div style="display: flex; flex-wrap: wrap; gap: 10px;">';
    
    

    $allSlotsQuery = $conn->prepare("SELECT `slotnumber`, `status` FROM `parking_slots`");
    $allSlotsQuery->execute();
    $allSlotsResult = $allSlotsQuery->get_result();

    if ($allSlotsResult->num_rows > 0) {
        while ($slotRow = $allSlotsResult->fetch_assoc()) {
            $slotNumber = htmlspecialchars($slotRow['slotnumber'], ENT_QUOTES, 'UTF-8');
            $slotStatus = $slotRow['status'];
            $isAvailable = $slotStatus === 'available';
            $backgroundColor = $isAvailable ? '#f9f9f9' : '#ffcccb';
            $cursor = $isAvailable ? 'pointer' : 'not-allowed';
            $onClick = $isAvailable ? "onclick=\"selectSlot('{$slotNumber}')\"" : '';

            echo "<div id=\"slotDiv_{$slotNumber}\" style=\"flex: 1 1 30%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; text-align: center; background-color: {$backgroundColor}; transition: background-color 0.3s ease; cursor: {$cursor};\" {$onClick}>";
            echo "<input type=\"hidden\" id=\"slot_{$slotNumber}\" name=\"slot\" value=\"{$slotNumber}\">";
            echo "<label for=\"slot_{$slotNumber}\" style=\"font-size: 14px;\">{$slotNumber}</label>";
            echo '</div>';
        }
    } else {
        echo '<p style="color: red; font-size: 14px;">No slots available</p>';
    }

    echo '</div>';
    echo '<input type="hidden" name="check_plate" value="1">';
    echo '<input type="hidden" name="plate" value="' . htmlspecialchars($stored_plate, ENT_QUOTES, 'UTF-8') . '">';
    echo '<button type="submit" style="padding: 10px 20px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; transition: background-color 0.3s ease;">Assign Slot</button>';
    echo '</form>';
    echo '</div>';

    echo '<script>
        let selectedSlot = null;

        function selectSlot(slotNumber) {
            const slotDivs = document.querySelectorAll("[id^=\'slotDiv_\']");
            slotDivs.forEach(div => {
                if (div.style.backgroundColor !== "rgb(255, 204, 203)") {
                    div.style.backgroundColor = "#f9f9f9";
                }
            });
            const selectedDiv = document.getElementById("slotDiv_" + slotNumber);
            if (selectedDiv.style.backgroundColor !== "rgb(255, 204, 203)") {
                selectedDiv.style.backgroundColor = "green";
                selectedSlot = slotNumber;
            }
        }

        document.getElementById("alreadyPaidButton").addEventListener("click", function() {
            const paidForm = document.getElementById("paidForm");
            const overlay = document.getElementById("overlay");
            const isHidden = paidForm.style.display === "none";
            paidForm.style.display = isHidden ? "block" : "none";
            overlay.style.display = isHidden ? "block" : "none";
        });

        document.getElementById("overlay").addEventListener("click", function() {
            document.getElementById("paidForm").style.display = "none";
            document.getElementById("overlay").style.display = "none";
        });

        document.querySelector("form").addEventListener("submit", function(event) {
            if (!selectedSlot) {
                event.preventDefault();
                alert("Please select an available slot before submitting.");
            } else {
                const slotInput = document.createElement("input");
                slotInput.type = "hidden";
                slotInput.name = "selected_slot";
                slotInput.value = selectedSlot;
                this.appendChild(slotInput);
            }
        });
    </script>';

    $allSlotsQuery->close();

    if (isset($_POST['selected_slot'])) {
        $slot = filter_input(INPUT_POST, 'selected_slot', FILTER_SANITIZE_STRING);

        // Check if the slot is still available
        $slotStatusQuery = $conn->prepare("SELECT `status` FROM `parking_slots` WHERE `slotnumber` = ?");
        $slotStatusQuery->bind_param("s", $slot);
        $slotStatusQuery->execute();
        $slotStatusResult = $slotStatusQuery->get_result();

        if ($slotStatusResult->num_rows > 0) {
            $slotStatusRow = $slotStatusResult->fetch_assoc();
            $slotStatus = $slotStatusRow['status'];

            if ($slotStatus === 'available') {
                // Update the slot in the database
                $updateSlotStmt = $conn->prepare("UPDATE `parking_slots` SET `plate` = ?, `assigned_time` = NOW(), `status` = 'not available' WHERE `slotnumber` = ?");
                $updateSlotStmt->bind_param("ss", $stored_plate, $slot);
                $updateSlotStmt->execute();
            
                // Insert login history
            $insertStmt = $conn->prepare("INSERT INTO `login_history` (`id`, `plate`, `login_time`) VALUES (?, ?, NOW())");
            $insertStmt->bind_param("is", $id, $stored_plate);
            $insertStmt->execute();
            $insertStmt->close();

                if ($updateSlotStmt->affected_rows > 0) {
                    error_log("Slot {$slot} assigned to plate {$stored_plate} and marked as not available.");
                    echo '<p style="color: green;">Slot successfully assigned!</p>';
                    echo '<p style="color: green;">You have parked in slot: ' . htmlspecialchars($slot, ENT_QUOTES, 'UTF-8') . '</p>';
                } else {
                    error_log("Failed to assign slot {$slot} to plate {$stored_plate}. Slot may not exist.");
                    echo '<p style="color: red;">Failed to assign the slot. Please try again.</p>';
                }

                $updateSlotStmt->close();
            } else {
                echo '<p style="color: red;">Error: The selected slot is already taken. Please choose a different slot.</p>';
            }
        } else {
            echo '<p style="color: red;">Invalid slot number. Please try again.</p>';
        }

        $slotStatusQuery->close();
    }
            break;

              case 'premium':
        
            // Automatically assign an available slot
            $availableSlotQuery = $conn->prepare("SELECT `slotnumber` FROM `parking_slots` WHERE `status` = 'available' LIMIT 1");
            $availableSlotQuery->execute();
            $availableSlotResult = $availableSlotQuery->get_result();
                 
            // Insert login history
            $insertStmt = $conn->prepare("INSERT INTO `login_history` (`id`, `plate`, `login_time`) VALUES (?, ?, NOW())");
            $insertStmt->bind_param("is", $id, $stored_plate);
            $insertStmt->execute();
            $insertStmt->close();

            if ($availableSlotResult->num_rows > 0) {
                $availableSlotRow = $availableSlotResult->fetch_assoc();
                $availableSlot = $availableSlotRow['slotnumber'];

                // Assign the available slot to the plate
                $assignSlotStmt = $conn->prepare("UPDATE `parking_slots` SET `plate` = ?, `assigned_time` = NOW(), `status` = 'not available' WHERE `slotnumber` = ?");
                $assignSlotStmt->bind_param("ss", $stored_plate, $availableSlot);
                $assignSlotStmt->execute();

                if ($assignSlotStmt->affected_rows > 0) {
                    error_log("Automatically assigned slot {$availableSlot} to plate {$stored_plate}.");
                    echo '<div class="premium-welcome" style="background-color: #e8f5e9; border-radius: 10px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 500px; margin: 50px auto; text-align: center; position: absolute; left: 50%; transform: translateX(-50%); font-family: \'Poppins\', sans-serif;">';
                    echo '<h3 style="color: #2e7d32; margin-bottom: 15px; font-weight: 600; font-family: \'Poppins\', sans-serif;">Welcome, Premium Member!</h3>';
                    echo '<p style="color: #495057; font-size: 16px; margin-bottom: 10px; font-family: \'Poppins\', sans-serif;">' . htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($lastname, ENT_QUOTES, 'UTF-8') . '</p>';
                    echo '<div style="background-color: #4caf50; color: white; padding: 15px; border-radius: 8px; margin-top: 15px;">';
                    echo '<p style="font-size: 14px; margin-bottom: 5px; font-family: \'Poppins\', sans-serif;">Your assigned parking slot</p>';
                    echo '<h2 style="font-size: 28px; margin: 0; font-family: \'Poppins\', sans-serif;">' . htmlspecialchars($availableSlot, ENT_QUOTES, 'UTF-8') . '</h2>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    error_log("Failed to assign slot {$availableSlot} to plate {$stored_plate}.");
                }

                $assignSlotStmt->close();
            } else {
                echo '<div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; max-width: 500px; margin: 20px auto; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">';
                echo '<p style="margin: 0; font-size: 16px;">No available slots at the moment. Please try again later.</p>';
                echo '</div>';
            }

            $availableSlotQuery->close();
       
            break;

            }
        } else {
            echo '<p style="color: red;">Error: The entered plate number does not match our records.</p>';
        }
    } else {
        echo '<div style="background-color: #f8d7da; border-radius: 8px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 400px; margin: 50px auto; text-align: center; font-family: \'Arial\', sans-serif;">';
        echo '<h3 style="color: #721c24; margin-bottom: 15px;">Plate Not Registered</h3>';
        echo '<p style="color: #721c24; margin-bottom: 20px;">This plate number is not registered in our system.</p>';
        echo '<a href="/parking/register.php" style="display: inline-block; background-color: #007bff; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; transition: background-color 0.3s;">Register Now</a>';
        echo '</div>';
    }
} else {
    echo '<p style="color: red;">Error: Invalid request. Please submit the form correctly.</p>';
}


if (isset($stmt) && $stmt !== false) {
    $stmt->close();
}   
$conn->close();
?>
