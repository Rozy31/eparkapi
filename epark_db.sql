create table admin (
    admin_id SERIAL NOT NULL PRIMARY KEY,
    admin_name VARCHAR(50) NOT NULL,
    admin_username VARCHAR(50) NOT NULL,
    admin_password VARCHAR(255) NOT NULL,
    position VARCHAR(20),
    date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

create table logs(
    id SERIAL NOT NULL PRIMARY KEY,
    admin_id INT REFERENCES admin (admin_id),
    time_in TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    time_out TIMESTAMP
);

create table parkings (
    slot_id SERIAL NOT NULL PRIMARY KEY,
    availability VARCHAR(30) NOT NULL,
    date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

create table rates (
    rate_id SERIAL NOT NULL PRIMARY KEY,
    rate_price DOUBLE PRECISION NOT NULL,
    rate_type VARCHAR(30) NOT NULL,
    date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

create table users (
    user_id SERIAL NOT NULL PRIMARY KEY,
    user_name VARCHAR(50) NOT NULL,
    user_email VARCHAR(50) NOT NULL,
    user_mobile VARCHAR(20) NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

create table bookings (
    booking_id SERIAL NOT NULL PRIMARY KEY,
    slot_id INT REFERENCES parkings (slot_id),
    user_id INT REFERENCES users (user_id),
    plate_num VARCHAR(30) NOT NULL,
    book_status VARCHAR(30) NOT NULL,
    date_entry TIMESTAMP,
    date_exit TIMESTAMP,
    total_price DOUBLE PRECISION,
    date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);