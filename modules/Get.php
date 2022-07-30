<?php

class Get
{
    protected $pdo, $gm;

    public function __construct(\PDO $pdo)
    {
        $this->gm = new GlobalMethods($pdo);
        $this->pdo = $pdo;
    }

    public function getSlot()
    {
        $payload = [];
        $code = 404;
        $remarks = "failed";
        $message = "Unable to save data";

        try {
            $sql = "SELECT * FROM parkings ";
            $res = $this->gm->retrieve($sql);

            if ($res['code'] == 200) {
                $payload = $res['data'];
                $code = 200;
                $remarks = "success";
                $message = "Successfully retrieved requested records";
            }
            return $this->gm->response($payload, $remarks, $message, $code);
        } catch (\PDOException $e) {
            return $this->gm->response($payload, $remarks, $message, $code);
        }
    }
    public function getRate()
    {
        $payload = [];
        $code = 404;
        $remarks = "failed";
        $message = "Unable to save data";

        try {
            $sql = "SELECT * FROM rates ";
            $res = $this->gm->retrieve($sql);

            if ($res['code'] == 200) {
                $payload = $res['data'];
                $code = 200;
                $remarks = "success";
                $message = "Successfully retrieved requested records";
            }
            return $this->gm->response($payload, $remarks, $message, $code);
        } catch (\PDOException $e) {
            return $this->gm->response($payload, $remarks, $message, $code);
        }

    }

    public function getAllBookings()
    {
        $payload = [];
        $code = 404;
        $remarks = "failed";
        $message = "Unable to save data";
        try {
            $sql = "SELECT bookings.booking_id,
            bookings.slot_id,
            bookings.user_id,
            bookings.plate,
            bookings.book_status,
            bookings.date_entry,
            bookings.date_exit,
            bookings.total_price,
            bookings.park_hrs,
            bookings.paid_date,
            users.user_name,
            users.user_mobile
             FROM bookings INNER JOIN users ON bookings.user_id = users.user_id
                            WHERE bookings.book_status != 'done' ORDER BY bookings.book_status DESC";

            $res = $this->gm->retrieve($sql);
            
            if ($res['code'] == 200) {

                foreach($res['data'] as $res){
                    $park_hrs = $res['park_hrs'];

                    if($res['date_entry'] != null){
                     
                        $time = new DateTime($res['date_entry']);
                        $time->add(new DateInterval("PT" . $park_hrs . "H"));
                        $date_park_hrs = $time->format('Y-m-d H:m:s');
                      
                    }else{
                        $date_park_hrs = 0;
                    }
                    
                    


                    array_push($payload,[
                        'booking_id' => $res['booking_id'],	
                        'slot_id' => $res['slot_id'],	
                        'user_id' => $res['user_id'],	
                        'Plate' => $res['plate'],	
                        'book_status' => $res['book_status'],	
                        'park_hrs' => 	$date_park_hrs,
                        'date_entry' => $res['date_entry'],	
                        'date_exit' => $res['date_exit'],	
                        'total_price' => $res['total_price'],	
                        'paid_date' => $res['paid_date'],
                        'slot_id' => $res['slot_id'],
                        'user_name' => $res['user_name'],
                        'user_mobile' => $res['user_mobile']
                    ]);
                }
                $payload = $payload;
                $code = 200;
                $remarks = "success";
                $message = "Successfully retrieved requested records";
            }
            return $this->gm->response($payload, $remarks, $message, $code);
        } catch (\PDOException $e) {
            return $this->gm->response($payload, $remarks, $message, $code);
        }
    }

    public function getUserBookings($data)
    {
        $payload = [];
        $code = 404;
        $remarks = "failed";
        $message = "Unable to save data";

        try {
            $sql = "SELECT bookings.booking_id,bookings.slot_id,bookings.user_id,bookings.plate,
                            bookings.book_status,bookings.date_entry,bookings.date_exit,bookings.total_price,
                            users.user_name,users.user_mobile,bookings.paid_date
                             FROM bookings INNER JOIN users ON bookings.user_id = users.user_id
                                            WHERE users.user_id = $data->id 
                                            AND book_status != 'cancel' 
                                            AND book_status != 'exited' 
                                            AND book_status != 'expired' ";
            $res = $this->gm->retrieve($sql);

            if ($res['code'] == 200) {
                $payload = $res['data'];
                $code = 200;
                $remarks = "success";
                $message = "Successfully retrieved requested records";
            }
            return $this->gm->response($payload, $remarks, $message, $code);
        } catch (\PDOException $e) {
            return $this->gm->response($payload, $remarks, $message, $code);
        }
    }

    public function searchDate($data)
    {
        $payload = [];
        $code = 404;
        $remarks = "failed";
        $message = "Unable to save data";

        try {
            $sql = "SELECT * FROM bookings WHERE date_entry LIKE '%$data->date%'";
            $res = $this->gm->retrieve($sql);

            if ($res['code'] == 200) {
                $payload = $res['data'];
                $code = 200;
                $remarks = "success";
                $message = "Successfully retrieved requested records";
            }
            return $this->gm->response($payload, $remarks, $message, $code);
        } catch (\PDOException $e) {
            return $this->gm->response($payload, $remarks, $message, $code);
        }
    }


    // too complicated codes for me dont touch till its done
    public function getTodayBookings()
    {
        $payload = [];
        $newpayload = [];
        $code = 404;
        $remarks = "failed";
        $message = "Unable to save data";
        $today = date('Y-m-d');

        try {
            $sql1 = "SELECT * FROM bookings WHERE book_status != 'cancel' AND DATE(date_created) = '$today'
                                            AND book_status != 'expired' AND book_status != 'exited' ";
            
            $sql2 = "SELECT * FROM parkings WHERE parkings.availability = 'Active' ";
            $sql4 = "SELECT * FROM parkings ";

            // for unpaid and paid user that doest entry yer
            // june 27 remove WHERE paid_date = null
            $sql3 = "SELECT * FROM bookings WHERE book_status = 'unpaid' OR book_status = 'paid'  AND DATE(date_created) = '$today'";
           
             $res1 = $this->gm->retrieve($sql1);
             $res2 = $this->gm->retrieve($sql2);
             $res3 = $this->gm->retrieve($sql3);
             $res4 = $this->gm->retrieve($sql4);

            if ( $res2['code'] == 200 && $res1['code'] == 200) {
                
                   foreach($res4['data'] as $park)
                    {
                        $needle = $park['slot_id'];
                        $index = array_search($needle, array_column($res1['data'], 'slot_id'));
                        
                        // if park has park id is equal to book id
                        if($index !== false){

                            // check if db has paid and unpaid user
                            if($res3['code'] == 200){

                                $needle2 = $park['slot_id'];
                                $index2 = array_search($needle2, array_column($res3['data'], 'slot_id'));
                        
                                if ($index2 !== false) {
                                    
                                    array_push($newpayload,[
                                        'Slot_id'=>$park['slot_id'],
                                        'Availability'=>'Reserved'
                                    ]);

                                }else{
                                    array_push($newpayload,[
                                        'Slot_id'=>$park['slot_id'],
                                        'Availability'=>'Occupied'
                                    ]);                            
                                }
                            }


                        }else{

                            $needle3 = $park['slot_id'];
                            $index3 = array_search($needle3, array_column($res2['data'], 'slot_id'));
                            if ($index3 !== false) {

                                array_push($newpayload,[
                                    'Slot_id'=>$park['slot_id'],
                                    'Availability'=>'Available'
                                ]);

                            }else{
                                array_push($newpayload,[
                                    'Slot_id'=>$park['slot_id'],
                                    'Availability'=>'Maintenance'
                                ]);
                            }
                        }
                    }

                    
              
            }else{
                // if no booking at all
                foreach($res4['data'] as $park)
                {
                    $needle3 = $park['slot_id'];
                    $index3 = array_search($needle3, array_column($res2['data'], 'slot_id'));
                    if ($index3 !== false) {

                        array_push($newpayload,[
                            'Slot_id'=>$park['slot_id'],
                            'Availability'=>'Available'
                        ]);

                    }else{
                        array_push($newpayload,[
                            'Slot_id'=>$park['slot_id'],
                            'Availability'=>'Maintenance'
                        ]);
                    }
                }
            }

            $payload = $newpayload;
            $code = 200;
            $remarks = "success";
            $message = "Successfully retrieved requested records";
            return $this->gm->response($payload, $remarks, $message, $code);
        } catch (\PDOException $e) {
            return $this->gm->response($payload, $remarks, $message, $code);
        }
    }

    function getAlluser()
    {
            $payload = [];
            $code = 404;
            $remarks = "failed";
            $message = "Unable to save data";
    
            try {
                $sql = "SELECT * FROM users ";
                $res = $this->gm->retrieve($sql);
    
                if ($res['code'] == 200) {
                    $payload = $res['data'];
                    $code = 200;
                    $remarks = "success";
                    $message = "Successfully retrieved requested records";
                }
                return $this->gm->response($payload, $remarks, $message, $code);
            } catch (\PDOException $e) {
                return $this->gm->response($payload, $remarks, $message, $code);
            }
        
    }

    function getForchart()
    {
        $payload = [];
        $total = [];
        $code = 404;
        $remarks = "failed";
        $message = "Unable to save data";
        $today = date('Y-m-d');
        $d = [];
        $replace = array();
        $replaced = array();
        $num = array();
        $count = 0;
        try {
            $sql = "SELECT DATE(paid_date) as paid_date,SUM(total_price) AS total FROM bookings GROUP BY DATE(paid_date) ORDER BY paid_date";

            $res = $this->gm->retrieve($sql);
            
            if ($res['code'] == 200) {
                
                $payload = $res['data'];
                $code = 200;
                $remarks = "success";
                $message = "Successfully retrieved requested records";
            }
            return $this->gm->response($payload, $remarks, $message, $code);
        } catch (\PDOException $e) {
            return $this->gm->response($payload, $remarks, $message, $code);
        }
    }

    
    function getEmployee()
    {
            $payload = [];
            $code = 404;
            $remarks = "failed";
            $message = "Unable to save data";
    
            try {
                $sql = "SELECT * FROM admin WHERE position = 'clerk' ";
                $res = $this->gm->retrieve($sql);
    
                if ($res['code'] == 200) {
                    $payload = $res['data'];
                    $code = 200;
                    $remarks = "success";
                    $message = "Successfully retrieved requested records";
                }
                return $this->gm->response($payload, $remarks, $message, $code);
            } catch (\PDOException $e) {
                return $this->gm->response($payload, $remarks, $message, $code);
            }
        
    }
}
