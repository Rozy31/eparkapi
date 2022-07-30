<?php
class Patch
{
    protected $pdo, $gm;

    public function __construct(\PDO $pdo)
    {
        $this->gm = new GlobalMethods($pdo);
        $this->pdo = $pdo;
    }

    public function updateSlotstatus($data)
    {
        $payload = [];
        $code = 404;
        $remarks = "failed";
        $message = "Unable to update data";

        try {
            $this->pdo->beginTransaction();

            $updateUserSQL = "UPDATE parkings SET availability =? WHERE slot_id = ?;";
            $updateUserSQL = $this->pdo->prepare($updateUserSQL);
            $updateUserSQL->execute([$data->availability, $data->slot_id]);
            $this->pdo->commit();

            $payload = $data;
            $code = 200;
            $remarks = "success";
            $message = "Successfully created";
            return $this->gm->response($payload, $remarks, $message, $code);
        } catch (\PDOException $e) {
            $this->pdo->rollback();
            throw $e;
        }

        return $this->gm->response($payload, $remarks, $message, $code);
    }


    public function updateBookingstatus($data)
    {
        $payload = [];
        $code = 404;
        $remarks = "failed";
        $message = "Unable to update data";
        $today = date("Y-m-d H:i:s");
        try {
            $this->pdo->beginTransaction();

            if ($data->book_status != 'paid') {
                $updateUserSQL = "UPDATE bookings SET book_status = ? WHERE booking_id = ?";
                $updateUserSQL = $this->pdo->prepare($updateUserSQL);
                $updateUserSQL->execute([$data->book_status, $data->booking_id]);
                $this->pdo->commit();
            } else {
                $updateUserSQL = "UPDATE bookings SET book_status = ?, paid_date = ? WHERE booking_id = ?";
                $updateUserSQL = $this->pdo->prepare($updateUserSQL);
                $updateUserSQL->execute([$data->book_status, $today, $data->booking_id]);
                $this->pdo->commit();
            }

            $payload = $data;
            $code = 200;
            $remarks = "success";
            $message = "Successfully created";
            return $this->gm->response($payload, $remarks, $message, $code);
        } catch (\PDOException $e) {
            $this->pdo->rollback();
            throw $e;
        }

        return $this->gm->response($payload, $remarks, $message, $code);
    }

    public function updateProfileuser($data)
    {
        $payload = [];
        $code = 404;
        $remarks = "failed";
        $message = "Unable to update data";

        try {
            $this->pdo->beginTransaction();
            if ($data->password == null) {
                $updateUserSQL = "UPDATE users SET user_name = ?, user_email = ?, user_mobile = ? WHERE user_id = ?";
                $updateUserSQL = $this->pdo->prepare($updateUserSQL);
                $updateUserSQL->execute([$data->name, $data->email, $data->mobile, $data->id]);
                $this->pdo->commit();
            } else {



                $updateUserSQL = "UPDATE users SET user_name = ?, user_email = ?, user_mobile = ?, user_password = ? WHERE user_id = ?";
                $updateUserSQL = $this->pdo->prepare($updateUserSQL);
                $updateUserSQL->execute([$data->name, $data->email, $data->mobile, password_hash($data->password, PASSWORD_DEFAULT), $data->id]);
                $this->pdo->commit();
            }

            $payload = $data;
            $code = 200;
            $remarks = "success";
            $message = "Successfully created";
            return $this->gm->response($payload, $remarks, $message, $code);
        } catch (\PDOException $e) {
            $this->pdo->rollback();
            throw $e;
        }

        return $this->gm->response($payload, $remarks, $message, $code);
    }

    public function updateRates($data)
    {
        $payload = [];
        $code = 404;
        $remarks = "failed";
        $message = "Unable to update data";

        try {
            $this->pdo->beginTransaction();

            $updateUserSQL = "UPDATE rates SET rate_price = ?, rate_type = ? WHERE rate_id = ?";
            $updateUserSQL = $this->pdo->prepare($updateUserSQL);
            $updateUserSQL->execute([$data->rate_price, $data->rate_type, $data->rate_id]);
            $this->pdo->commit();

            $payload = $data;
            $code = 200;
            $remarks = "success";
            $message = "Successfully created";
            return $this->gm->response($payload, $remarks, $message, $code);
        } catch (\PDOException $e) {
            $this->pdo->rollback();
            throw $e;
        }

        return $this->gm->response($payload, $remarks, $message, $code);
    }
    public function updateProfileclerk($data)
    {
        $payload = [];
        $code = 404;
        $remarks = "failed";
        $message = "Unable to update data";

        try {
            $this->pdo->beginTransaction();
            if ($data->password == null) {
                $updateUserSQL = "UPDATE admin SET admin_name = ?, admin_username = ? WHERE admin_id = ?";
                $updateUserSQL = $this->pdo->prepare($updateUserSQL);
                $updateUserSQL->execute([$data->name, $data->email, $data->id]);
                $this->pdo->commit();
            } else {
                $updateUserSQL = "UPDATE admin SET admin_name = ?, admin_username = ?,admin_password = ? WHERE admin_id = ?";
                $updateUserSQL = $this->pdo->prepare($updateUserSQL);
                $updateUserSQL->execute([$data->name, $data->email, password_hash($data->password, PASSWORD_DEFAULT), $data->id]);
                $this->pdo->commit();
            }

            $sql = "SELECT * FROM admin WHERE admin_id = ? LIMIT 1";
            $sql = $this->pdo->prepare($sql);
            $sql->execute([
                $data->id,
            ]);

            $res = $sql->fetch(PDO::FETCH_ASSOC);

            $payload = [
                "id" => $res['admin_id'],
                "name" => $res['admin_name'],
                "position" => $res['position'],
                "username" => $res['admin_username']
            ];
            $code = 200;
            $remarks = "success";
            $message = "Successfully retrieved and updated requested records";

            return $this->gm->response($payload, $remarks, $message, $code);
        } catch (\PDOException $e) {
            $this->pdo->rollback();
            throw $e;
        }

        return $this->gm->response($payload, $remarks, $message, $code);
    }

    public function updatePark($data)
    {
        $payload = [];
        $code = 404;
        $remarks = "failed";
        $message = "Unable to update data";

        try {
            $this->pdo->beginTransaction();

            $updateUserSQL = "UPDATE parkings SET availability = ? WHERE slot_id = ?";
            $updateUserSQL = $this->pdo->prepare($updateUserSQL);
            $updateUserSQL->execute([$data->availability, $data->slot_id]);
            $this->pdo->commit();

            $payload = [];
            $code = 200;
            $remarks = "success";
            $message = "Successfully retrieved and updated requested records";

            return $this->gm->response($payload, $remarks, $message, $code);
        } catch (\PDOException $e) {
            $this->pdo->rollback();
            throw $e;
        }

        return $this->gm->response($payload, $remarks, $message, $code);
    }

    public function updateClerk($data)
    {
        $payload = [];
        $code = 404;
        $remarks = "failed";
        $message = "Unable to update data";

        try {
            $this->pdo->beginTransaction();
            if ($data->admin_password != null) {
                $updateUserSQL = "UPDATE admin SET admin_name = ?, admin_username = ? ,admin_password = ? WHERE admin_id = ?";
                $updateUserSQL = $this->pdo->prepare($updateUserSQL);
                $updateUserSQL->execute([$data->admin_name, $data->admin_username, password_hash($data->admin_password, PASSWORD_DEFAULT), $data->admin_id]);
                $this->pdo->commit();
            } else {
                $updateUserSQL = "UPDATE admin SET admin_name = ?,admin_username = ? WHERE admin_id = ?";
                $updateUserSQL = $this->pdo->prepare($updateUserSQL);
                $updateUserSQL->execute([$data->admin_name, $data->admin_username, $data->admin_id]);
                $this->pdo->commit();
            }

            $payload = [];
            $code = 200;
            $remarks = "success";
            $message = "Successfully retrieved and updated requested records";

            return $this->gm->response($payload, $remarks, $message, $code);
        } catch (\PDOException $e) {
            $this->pdo->rollback();
            throw $e;
        }

        return $this->gm->response($payload, $remarks, $message, $code);
    }

    public function scan($data)
    {
        $payload = [];
        $code = 404;
        $remarks = "failed";
        $message = "Unable to update data";
        $today = date('Y-m-d H:i:s');
        $exited = 'exited';

        try {
            $this->pdo->beginTransaction();

            $sql = "SELECT * FROM bookings WHERE booking_id = $data->booking_id";
            $res = $this->gm->retrieve($sql);

            foreach ($res['data'] as $book) {
                // if date entry has value then the scan data will go to exit data
                if ($book['date_entry'] == null) {
                    $updateUserSQL = "UPDATE bookings SET date_entry = ? WHERE booking_id = ?";
                    $updateUserSQL = $this->pdo->prepare($updateUserSQL);
                    $updateUserSQL->execute([$today, $data->booking_id]);
                    $this->pdo->commit();
                } else {
                    $currentDate = strtotime($book['date_entry']);
                    $futureDate = $currentDate + (60 * 3);
                    $formatDate = date("Y-m-d H:i:s", $futureDate);

                    if ($today >= $formatDate && $book['date_entry'] != null) {
                        $updateUserSQL = "UPDATE bookings SET book_status = ? ,date_exit = ? WHERE booking_id = ?";
                        $updateUserSQL = $this->pdo->prepare($updateUserSQL);
                        $updateUserSQL->execute([$exited, $today, $data->booking_id]);
                        $this->pdo->commit();
                    }
                }
            }

            $code = 200;
            $remarks = "success";
            $message = "Successfully retrieved and updated requested records";


            return $this->gm->response($payload, $remarks, $message, $code);
        } catch (\PDOException $e) {
            $this->pdo->rollback();
            throw $e;
        }

        return $this->gm->response($payload, $remarks, $message, $code);
    }
}
