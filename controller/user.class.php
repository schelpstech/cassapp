<?php

class User
{
    // Refer to database connection
    private $db;

    // Instantiate object with database connection
    public function __construct($db_conn)
    {
        $this->db = $db_conn;
    }


public function recordLog($object, $activity, $description)
{
    try {

        $sql = "INSERT INTO log 
                (user_name, uip, object, activity, description) 
                VALUES 
                (:user, :userip, :object, :activity, :description)";

        $query = $this->db->prepare($sql);

        // Resolve user safely
        $user = $_SESSION['activeAdmin']
            ?? $_SESSION['active']
            ?? $object
            ?? 'system';

        // Resolve IP address safely
        $userIp = $_SERVER['HTTP_X_FORWARDED_FOR']
            ?? $_SERVER['REMOTE_ADDR']
            ?? '0.0.0.0';

        // Optional sanitization / truncation
        $object      = substr((string)$object, 0, 100);
        $activity    = substr((string)$activity, 0, 100);
        $description = substr((string)$description, 0, 255);

        $query->bindParam(':user', $user);
        $query->bindParam(':userip', $userIp);
        $query->bindParam(':object', $object);
        $query->bindParam(':activity', $activity);
        $query->bindParam(':description', $description);

        $query->execute();

    } catch (PDOException $e) {
        // Do not break application flow for logging failure
        error_log('LOG INSERT ERROR: ' . $e->getMessage());
    }
}
}
?>

