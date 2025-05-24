<?php
    include('db/connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/logi.png" type="image/x-icon">
    <title>ParkEase</title>  
    <link rel="stylesheet" href="css/style.css">
    <style>
        h2 {
            font-size: 1.5em;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        
    </style>
</head>
<body>
       
    <div class="container">
        <div class="form-container">
            <div class="pormis">
            <a href="db/authenticate.php" c>
                <img src="images/logi.png" alt="">
            </a>
            <form action="db/checkPlate.php" id="check_plate" method="post">
                <label for="plate">ENTER PLATE NUMBER</label>
                <input type="text" id="plate" name="plate" required oninput="this.value = this.value.replace(/\s+/g, '-');">
                <button type="submit" name="check_plate">submit</button> 

                <p style="margin: 10px;">Not Registered? 
                    <a href="register.php">Click Here</a>
                </p> 
                

            </form>

           
            
        
            </div>
            <div class="slot">
                <h2>Available Parking Slots</h2>
                <div class="slot-container" id="slotContainer">
                    <?php
                        $query = "SELECT slotnumber, status FROM parking_slots";
                        $result = mysqli_query($conn, $query);
                        $slotCount = mysqli_num_rows($result);

                        echo "<div id='slotWrapper' style='display: flex; flex-wrap: wrap; gap: 15px; padding: 8px; cursor: pointer;'>";
                        if ($result && $slotCount > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $status = $row['status'];
                                $statusColor = ($status == 'available') ? '#28a745' : '#dc3545';
                                $statusText = ($status == 'available') ? 'Available' : 'Not Available';
                                
                                echo "<div class='slot-item' style='flex: 1 0 100px; padding: 12px; border: 1px solid #ddd; border-radius: 6px; background-color: #ffffff; box-shadow: 0 3px 4px rgba(0, 0, 0, 0.1); text-align: center;'>";
                                echo "<strong style='font-size: 0.85em; color: #333;'>Slot " . htmlspecialchars($row['slotnumber']) . "</strong>";
                                echo "<p style='margin-top: 6px; color: {$statusColor}; font-size: 0.75em; font-weight: bold;'>{$statusText}</p>";
                                echo "</div>";
                            }
                        } else {
                            echo "<div class='slot-item' style='flex: 1 0 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; background-color: #ffffff; box-shadow: 0 3px 4px rgba(0, 0, 0, 0.1); text-align: center;'>";
                            echo "<p style='color: #dc3545; font-size: 0.85em; font-weight: bold;'>No slots found</p>";
                            echo "</div>";
                        }
                        echo "</div>";
                    ?>
                </div>
                <button id="nextSlideBtn" style="margin-top: 10px; padding: 5px 10px; background-color: hsl(229, 67%, 34%); color: white; border: none; border-radius: 5px; cursor: pointer;">Next Slide</button>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const slotContainer = document.getElementById('slotContainer');
                        const slotWrapper = document.getElementById('slotWrapper');
                        const nextSlideBtn = document.getElementById('nextSlideBtn');
                        const slotCount = <?php echo $slotCount; ?>;
                        
                        if (slotCount > 12) {
                            let currentIndex = 0;
                            const itemsPerSlide = 12;
                            const totalSlides = Math.ceil(slotCount / itemsPerSlide);

                            function showSlide(index) {
                                const start = index * itemsPerSlide;
                                const end = start + itemsPerSlide;
                                const items = Array.from(slotWrapper.children);
                                items.forEach((item, i) => {
                                    item.style.display = (i >= start && i < end) ? 'block' : 'none';
                                });
                            }

                            function nextSlide() {
                                currentIndex = (currentIndex + 1) % totalSlides;
                                showSlide(currentIndex);
                            }

                            showSlide(0);
                            setInterval(nextSlide, 5000); // Change slide every 5 seconds

                            // Make it clickable to the next slide
                            slotWrapper.addEventListener('click', nextSlide);
                            nextSlideBtn.addEventListener('click', nextSlide);
                        } else {
                            nextSlideBtn.style.display = 'none';
                        }
                    });
                </script>
            </div>
        </div>
    </div>

   
                        <script>
                            
                        </script>
   <script src="script/script.js"></script>
</body>
</html>