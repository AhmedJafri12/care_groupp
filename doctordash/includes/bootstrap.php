<?php
// Doctor Dashboard bootstrap: session + DB helpers (MySQL via config/db.php)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';

function dd_current_doctor() {
    return $_SESSION['doctor'] ?? null;
}

function dd_is_logged_in() {
    return !!dd_current_doctor();
}

function dd_require_login() {
    if (!dd_is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function dd_find_doctor_by_email($email) {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id, name, email, contact_phone, password_hash, specialty, clinic, degree, bio FROM doctors WHERE email = ?');
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function dd_find_doctor_by_id($id) {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id, name, email, contact_phone, specialty, clinic, degree, bio FROM doctors WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function dd_login($email, $password) {
    $u = dd_find_doctor_by_email($email);
    if ($u && !empty($u['password_hash']) && password_verify($password, $u['password_hash'])) {
        $_SESSION['doctor'] = [
            'id' => (int)$u['id'],
            'name' => $u['name'],
            'email' => $u['email'],
            'specialty' => $u['specialty'] ?? '',
            'contact_phone' => $u['contact_phone'] ?? '',
        ];
        return true;
    }
    return false;
}

function dd_logout() {
    $_SESSION['doctor'] = null;
    unset($_SESSION['doctor']);
}

function dd_get_profile($id) {
    return dd_find_doctor_by_id($id) ?: [];
}

function dd_save_profile($id, $profile) {
    $pdo = db();
    $fields = ['name','email','specialty','contact_phone','clinic','degree','bio'];
    $set = [];
    $vals = [];
    foreach ($fields as $f) {
        if (array_key_exists($f, $profile)) {
            $set[] = "$f = ?";
            $vals[] = $profile[$f];
        }
    }
    if (!$set) return;
    $vals[] = $id;
    $sql = 'UPDATE doctors SET ' . implode(', ', $set) . ' WHERE id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($vals);
}

function dd_get_appointments($doctor_id) {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id, patient_id, doctor_id, scheduled_at AS datetime, status, reason, notes FROM appointments WHERE doctor_id = ? ORDER BY scheduled_at ASC');
    $stmt->execute([$doctor_id]);
    return $stmt->fetchAll();
}

function dd_get_availability($doctor_id) {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT recurrence_type, weekday, month_day, slot_start, slot_end, notes FROM doctor_availability WHERE doctor_id = ? ORDER BY id ASC');
    $stmt->execute([$doctor_id]);
    $day = [];
    $week = [];
    $month = [];
    foreach ($stmt as $row) {
        if ($row['recurrence_type'] === 'weekly') {
            $label = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][max(0,min(6,(int)$row['weekday']))] ?? 'Mon';
            $rng = trim($row['notes'] ?? '');
            $week[] = $rng ? "$label $rng" : $label;
        } else {
            // Treat any non-weekly as explicit slot
            if (!empty($row['slot_start'])) $day[] = date('c', strtotime($row['slot_start']));
        }
    }
    return ['day'=>$day,'week'=>$week,'month'=>$month];
}

function dd_save_availability($doctor_id, $availability) {
    $pdo = db();
    // Simple strategy: clear and reinsert
    $pdo->prepare('DELETE FROM doctor_availability WHERE doctor_id = ?')->execute([$doctor_id]);

    // Day: ISO datetimes -> store as explicit slots
    foreach (($availability['day'] ?? []) as $iso) {
        $dt = trim($iso);
        if ($dt === '') continue;
        $ins = $pdo->prepare('INSERT INTO doctor_availability (doctor_id, recurrence_type, slot_start) VALUES (?,?,?)');
        $ins->execute([$doctor_id, 'none', $dt]);
    }

    // Week: lines like "Mon 10:00-12:00"
    foreach (($availability['week'] ?? []) as $line) {
        $line = trim($line);
        if ($line === '') continue;
        // Parse weekday
        if (preg_match('/^(Sun|Mon|Tue|Wed|Thu|Fri|Sat)\s*(.*)$/i', $line, $m)) {
            $map = ['sun'=>0,'mon'=>1,'tue'=>2,'wed'=>3,'thu'=>4,'fri'=>5,'sat'=>6];
            $wd = $map[strtolower($m[1])] ?? 1;
            $notes = trim($m[2] ?? '');
            $ins = $pdo->prepare('INSERT INTO doctor_availability (doctor_id, recurrence_type, weekday, notes) VALUES (?,?,?,?)');
            $ins->execute([$doctor_id, 'weekly', $wd, $notes]);
        }
    }

    // Month: treat as explicit day slots if provided (ISO date)
    foreach (($availability['month'] ?? []) as $d) {
        $d = trim($d);
        if ($d === '') continue;
        $ins = $pdo->prepare('INSERT INTO doctor_availability (doctor_id, recurrence_type, slot_start) VALUES (?,?,?)');
        $ins->execute([$doctor_id, 'none', $d]);
    }
}
