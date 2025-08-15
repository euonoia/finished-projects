<?php
require_once '../db/config.php';

$incidents = $conn->query("
    SELECT i.id, u.username, i.latitude, i.longitude, i.timestamp 
    FROM incidents i
    JOIN users u ON i.user_id = u.id
    WHERE i.status = 'active'
    ORDER BY i.timestamp DESC
");

if ($incidents->num_rows > 0): ?>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php while ($incident = $incidents->fetch_assoc()): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($incident['username']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="https://www.google.com/maps?q=<?php echo $incident['latitude']; ?>,<?php echo $incident['longitude']; ?>" 
                           target="_blank" class="text-blue-500 hover:underline">
                            <?php echo htmlspecialchars($incident['latitude']); ?>, <?php echo htmlspecialchars($incident['longitude']); ?>
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo date('M j, Y g:i A', strtotime($incident['timestamp'])); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <form method="POST" action="resolve_incident.php" class="inline">
                            <input type="hidden" name="incident_id" value="<?php echo $incident['id']; ?>">
                            <button type="submit" class="text-green-500 hover:text-green-700">
                                <i class="fas fa-check"></i> Resolve
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="text-gray-500">No active incidents</p>
<?php endif; ?>