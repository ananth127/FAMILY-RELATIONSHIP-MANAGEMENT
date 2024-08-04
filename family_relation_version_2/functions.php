<?php
// Function to get ancestors with levels
function getAncestorsWithLevels($pdo, $personId) {
    $ancestors = [];
    $queue = [[$personId, 0]]; // [id, level]
    $visited = [];
    
    while (!empty($queue)) {
        list($currentId, $level) = array_shift($queue);
        if (in_array($currentId, $visited)) {
            continue;
        }
        $visited[] = $currentId;
        
        $stmt = $pdo->prepare("SELECT father_id, mother_id FROM userss WHERE id = ?");
        $stmt->execute([$currentId]);
        $person = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($person) {
            if ($person['father_id']) {
                $ancestors[] = ['id' => $person['father_id'], 'level' => $level + 1];
                $queue[] = [$person['father_id'], $level + 1];
            }
            if ($person['mother_id']) {
                $ancestors[] = ['id' => $person['mother_id'], 'level' => $level + 1];
                $queue[] = [$person['mother_id'], $level + 1];
            }
        }
    }
    return $ancestors;
}

// Function to get descendants with levels
function getDescendantsWithLevels($pdo, $personId) {
    $descendants = [];
    $queue = [[$personId, 0]]; // [id, level]
    $visited = [];
    
    while (!empty($queue)) {
        list($currentId, $level) = array_shift($queue);
        if (in_array($currentId, $visited)) {
            continue;
        }
        $visited[] = $currentId;
        
        $stmt = $pdo->prepare("SELECT id FROM userss WHERE father_id = ? OR mother_id = ?");
        $stmt->execute([$currentId, $currentId]);
        $children = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        
        foreach ($children as $child) {
            $descendants[] = ['id' => $child, 'level' => $level + 1];
            $queue[] = [$child, $level + 1];
        }
    }
    return $descendants;
}

// Function to send a message to ancestors and descendants with levels
function sendMessageWithLevels($pdo, $senderId, $message) {
    $ancestors = getAncestorsWithLevels($pdo, $senderId);
    $descendants = getDescendantsWithLevels($pdo, $senderId);
    
    $recipients = array_merge($ancestors, $descendants);
    $recipientIds = array_column($recipients, 'id');
    
    foreach ($recipientIds as $recipientId) {
        // Check if a message already exists for this sender and receiver
        $stmt = $pdo->prepare("SELECT id FROM messages WHERE sender_id = ? AND recipient_id = ?");
        $stmt->execute([$senderId, $recipientId]);
        $existingMessage = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existingMessage) {
            // Update the existing message
            $update_stmt = $pdo->prepare("UPDATE messages SET message = ? WHERE sender_id = ? AND recipient_id = ?");
            $update_stmt->execute([$message, $senderId, $recipientId]);
        } else {
            // Insert a new message
            $insert_stmt = $pdo->prepare("INSERT INTO messages (sender_id, recipient_id, message) VALUES (?, ?, ?)");
            $insert_stmt->execute([$senderId, $recipientId, $message]);
        }
    }
    
    return $recipientIds;
}

// Function to get recipient names
function getRecipientNames($pdo, $recipientIds) {
    if (empty($recipientIds)) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($recipientIds), '?'));
    $stmt = $pdo->prepare("SELECT id, name FROM userss WHERE id IN ($placeholders)");
    $stmt->execute($recipientIds);
    $recipientNames = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
    return $recipientNames;
}
?>
