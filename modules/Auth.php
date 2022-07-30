<?php
class Auth
{
    protected $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function loginAdmin($data)
    {
        $payload = [];
        $remarks = "failed";
        $message = "No Record found";

        $sql = "SELECT * FROM admin WHERE admin_username = ? LIMIT 1";
        $sql = $this->pdo->prepare($sql);
        $sql->execute([
            $data->admin_username,
        ]);

        $res = $sql->fetch(PDO::FETCH_ASSOC);

        if ($res && password_verify($data->admin_password, $res['admin_password'])) {
            $payload = [
                "id" => $res['admin_id'],
                "name" => $res['admin_name'],
                "position" => $res['position'],
                "username" => $res['admin_username']
            ];
            $remarks = "success";
            $message = "Login success";
            $this->timeInLog($res['admin_id']);
            return response($payload, $remarks, $message);
        } else {
            $message = 'Incorrect username or password';
            return response($payload, $remarks, $message);
        }
    }

    public function registerAdmin($data)
    {
        $payload = [];
        $remarks = "failed";
        $message = "No Record inserted";
        $position = 'clerk';

        $sql = "SELECT * FROM admin WHERE admin_username = ?";
        $sql = $this->pdo->prepare($sql);
        $sql->execute([
            $data->admin_username
        ]);

        $count = $sql->rowCount();

        if ($count) {
            $message = 'Username already registered';
            return response($payload, $remarks, $message);
        } else {
            $sql = "INSERT INTO admin (admin_name, admin_username, admin_password,position) VALUES (?,?,?,?)";
            $sql = $this->pdo->prepare($sql);
            $sql->execute([
                $data->admin_name,
                $data->admin_username,
                password_hash($data->admin_password, PASSWORD_DEFAULT),
                $position
            ]);

            $count = $sql->rowCount();
            $LAST_ID = $this->pdo->lastInsertId();

            if ($count) {
                $payload = [
                    "id" => $LAST_ID,
                    "name" => $data->admin_name,
                    "username" => $data->admin_username,
                ];
                $remarks = "success";
                $message = "Account successfully created";
                return response($payload, $remarks, $message);
            } else {
                return response($payload, $remarks, $message);
            }
        }
    }

    public function loginUser($data)
    {
        $payload = [];
        $remarks = "failed";
        $message = "No Record found";

        $sql = "SELECT * FROM users WHERE user_email = ? LIMIT 1";
        $sql = $this->pdo->prepare($sql);
        $sql->execute([
            $data->user_email,
        ]);

        $res = $sql->fetch(PDO::FETCH_ASSOC);

        if ($res && password_verify($data->user_password, $res['user_password'])) {
            $payload = [
                "id" => $res['user_id'],
                "name" => $res['user_name'],
                "email" => $res['user_email'],
                "mobile" => $res['user_mobile'],
            ];
            $remarks = "success";
            $message = "Login success";
            return response($payload, $remarks, $message);
        } else {
            $message = 'Incorrect username or password';
            return response($payload, $remarks, $message);
        }
    }

    public function registerUser($data)
    {
        $payload = [];
        $remarks = "failed";
        $message = "No Record inserted";

        $sql = "SELECT * FROM users WHERE user_email = ?";
        $sql = $this->pdo->prepare($sql);
        $sql->execute([
            $data->user_email
        ]);

        $count = $sql->rowCount();

        if ($count) {
            $message = 'Email already registered';
            return response($payload, $remarks, $message);
        } else {
            $sql = "INSERT INTO users (user_name, user_email, user_mobile, user_password) VALUES (?,?,?,?)";
            $sql = $this->pdo->prepare($sql);
            $sql->execute([
                $data->user_name,
                $data->user_email,
                $data->user_mobile,
                password_hash($data->user_password, PASSWORD_DEFAULT),
            ]);

            $count = $sql->rowCount();
            $LAST_ID = $this->pdo->lastInsertId();

            if ($count) {
                $payload = [
                    "id" => $LAST_ID,
                    "name" => $data->user_name,
                    "email" => $data->user_email,
                    "mobile" => $data->user_mobile,
                ];
                $remarks = "success";
                $message = "Account successfully created";
                return response($payload, $remarks, $message);
            } else {
                return response($payload, $remarks, $message);
            }
        }
    }


    public function timeInLog($data)
    {
        try {
            $stmt = "INSERT INTO logs(admin_id) VALUES (?)";
            $stmt = $this->pdo->prepare($stmt);
            $stmt->execute([$data]);

            return ["message" => "success"];
        } catch (\PDOException $e) {
            return ["message" => "failed"];
        }
    }

    public function timeOutLog($data)
    {
        try {
            $stmt = "UPDATE logs SET time_out=NOW() WHERE admin_id=?";
            $stmt = $this->pdo->prepare($stmt);
            $stmt->execute([$data->admin_id]);

            return ["message" => "success"];
        } catch (\PDOException $e) {
            return ["message" => "failed"];
        }
    }
}
