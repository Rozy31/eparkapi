<?php

class Post
{
    protected $pdo, $gm;

    public function __construct(\PDO $pdo)
    {
        $this->gm = new GlobalMethods($pdo);
        $this->pdo = $pdo;
    }

    public function addReserve($data)
    {
        $payload = [];
        $code = 404;
        $remarks = "failed";
        $message = "Unable to retrieve data";

        $book_status = 'unpaid';

        try {
          
            $stmt = "INSERT INTO bookings(slot_id,user_id ,plate , book_status,park_hrs , total_price ) VALUES (?,?,?,?,?,?)";
            $stmt = $this->pdo->prepare($stmt);
            $stmt->execute([$data->slot_id,$data->user_id ,$data->plate ,$book_status ,$data->hrs,$data->total_price]);
            
            $code = 200;
            $remarks = "success";
            $message = "Successfully created";

            return $this->gm->response($payload, $remarks, $message, $code);
        } catch (\PDOException $e) {
            return $this->gm->response($payload, $remarks, $message, $code);
        }
    }

    public function addslot()
    {
        $payload = [];
        $code = 404;
        $remarks = "failed";
        $message = "Unable to retrieve data";
        $availability = 'Active';

        try {
          
            $stmt = "INSERT INTO parkings(availability ) VALUES (?)";
            $stmt = $this->pdo->prepare($stmt);
            $stmt->execute([$availability]);
            
            $code = 200;
            $remarks = "success";
            $message = "Successfully created";

            return $this->gm->response($payload, $remarks, $message, $code);
        } catch (\PDOException $e) {
            return $this->gm->response($payload, $remarks, $message, $code);
        }
    }

}
