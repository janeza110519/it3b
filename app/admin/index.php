<?php
require '../../config/config.php';

requireRole('admin');

$section = $_GET['section'] ?? '';

$action = $_GET['action'] ?? '';

if ($section === 'activity-logs'){
        // Activity Logs Query
        $stmt = $pdo->query("
        SELECT * 
        FROM activity_logs
        ORDER BY activity_log_created_at DESC
    ");

    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

} elseif ($section === 'user-sessions') {

    // User Sessions Query
    $stmt = $pdo->query("
        SELECT
            us.session_id,
            us.user_id,
            u.user_username,
            u.user_email,
            u.user_role,
            us.session_start,
            us.session_end,
            us.session_duration
        FROM user_sessions us
        INNER JOIN users u
            ON us.user_id = u.user_id
        ORDER BY us.session_start DESC
    ");

    $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.8/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/3.0.4/css/dataTables.bootstap5.min.css"/>

    <style>
        ```css
/* General Page Style */
body {
    background-color: #f4f6f9;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 25px;
    color: #343a40;
}

/* Main Heading */
h1 {
    font-size: 32px;
    font-weight: bold;
    color: #212529;
    margin-bottom: 15px;
}

/* Sign Out Button */
body > a.btn-danger {
    display: inline-block;
    padding: 10px 20px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: 0.3s;
}

body > a.btn-danger:hover {
    background-color: #bb2d3b;
    transform: translateY(-1px);
}

/* Horizontal Line */
hr {
    border: 0;
    border-top: 1px solid #d6d9de;
    margin: 25px 0;
}

/* Navigation Buttons */
nav {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 25px;
}

nav .btn {
    padding: 11px 20px;
    border-radius: 7px;
    font-size: 15px;
    font-weight: 600;
    transition: 0.3s;
}

nav .btn:hover {
    background-color: #084298;
    transform: translateY(-2px);
}

/* Section Headings */
h4 {
    font-size: 24px;
    font-weight: bold;
    color: #212529;
    margin: 20px 0;
    padding-bottom: 12px;
    border-bottom: 2px solid #0d6efd;
}

/* Table Layout */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background-color: #ffffff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.07);
    margin-bottom: 25px;
    table-layout: auto;
}

/* Table Header */
table thead th {
    background-color: #212529;
    color: #ffffff;
    padding: 15px 12px;
    text-align: left;
    font-size: 14px;
    font-weight: 600;
    white-space: normal;
    border: none;
}

/* Table Rows */
table tbody tr {
    transition: background-color 0.2s;
}

table tbody tr:nth-child(even) {
    background-color: #f8f9fa;
}

table tbody tr:nth-child(odd) {
    background-color: #ffffff;
}

table tbody tr:hover {
    background-color: #eaf2ff;
}

/* Table Cells */
table tbody td {
    padding: 13px 12px;
    font-size: 13px;
    color: #495057;
    vertical-align: middle;
    border-bottom: 1px solid #e9ecef;
    overflow-wrap: anywhere;
}

/* Remove Bottom Border from Last Row */
table tbody tr:last-child td {
    border-bottom: none;
}

/* Column Widths for Better Organization */
table th:nth-child(1),
table td:nth-child(1) {
    min-width: 75px;
}

table th:nth-child(2),
table td:nth-child(2) {
    min-width: 70px;
}

/* Make Long User Agent Text Easier to Read */
table th:nth-child(7),
table td:nth-child(7) {
    min-width: 220px;
    max-width: 320px;
    overflow-wrap: anywhere;
    white-space: normal;
    line-height: 1.5;
}

/* Improve Date and Time Display */
table th:last-child,
table td:last-child {
    min-width: 150px;
}

/* Responsive Design for Smaller Screens */
@media screen and (max-width: 768px) {
    body {
        padding: 15px;
    }

    h1 {
        font-size: 26px;
    }

    h4 {
        font-size: 21px;
    }

    nav {
        gap: 8px;
    }

    nav .btn {
        padding: 9px 12px;
        font-size: 13px;
    }

    table {
        min-width: 850px;
    }

    table thead th,
    table tbody td {
        padding: 10px;
        font-size: 12px;
    }
}
    </style>
</head>
<body>
        <h1>Welcome, Admin!</h1>

        <a href="../../auth/signout.php" class="btn btn-danger">
            Sign Out
        </a>
        <hr>

    <nav>
        <a href="index.php?section=activity-logs" class="btn btn-primary">Activity Logs </a>
        <a href="index.php?section=user-sessions" class="btn btn-primary">User Sessions </a>
    </nav>

    <?php if ($section === 'activity-logs'): ?>

        <h4>Activity Logs</h4>

        <table>

            <thead>
                <tr>
                    <th>Record ID</th>
                    <th>User ID</th>
                    <th>User Email</th>
                    <th>Action</th>
                    <th>Status</th>
                    <th>IP Address</th>
                    <th>User Agent</th>
                    <th>Date & Time</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($activities as $activity): ?>

                    <tr>
                        <td>
                            <?= htmlspecialchars($activity['activity_log_id']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($activity['user_id']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($activity['user_email']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($activity['activity_log_action']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($activity['activity_log_status']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($activity['activity_log_ip_address']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($activity['activity_log_user_agent']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($activity['activity_log_created_at']) ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php endif; ?>

    <?php if ($section === 'user-sessions'): ?>

        <h4>User Sessions</h4>

        <table>

            <thead>
                <tr>
                    <th>Session ID</th>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Session Start</th>
                    <th>Session End</th>
                    <th>Duration</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($sessions as $session): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($session['session_id']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($session['user_id']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($session['user_username']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($session['user_email']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($session['user_role']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($session['session_start']) ?>
                        </td>

                        <td>
                            <?= $session['session_end']
                                ? htmlspecialchars($session['session_end'])
                                : 'Active'
                            ?>
                        </td>

                        <td>

                            <?php

                            if ($session['session_duration'] !== null) {

                                $seconds = (int) $session['session_duration'];

                                $hours = floor($seconds / 3600);
                                $minutes = floor(($seconds % 3600) / 60);
                                $seconds = $seconds % 60;

                                echo sprintf(
                                    '%02d hr %02d min %02d sec',
                                    $hours,
                                    $minutes,
                                    $seconds
                                );

                            } else {

                                echo 'Active';

                            }

                            ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php endif; ?>
</body>
</html>