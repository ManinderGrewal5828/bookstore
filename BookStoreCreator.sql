CREATE TABLE `bookinventory` (
  `id` int(11) NOT NULL,
  `book_name` varchar(200) NOT NULL,
  `author` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `bookinventory`
  ADD PRIMARY KEY (`id`);

CREATE TABLE `checkout` (
  `id` int(11) NOT NULL,
  `book_id` int(10) NOT NULL,
  `first_name` varchar(200) NOT NULL,
  `last_name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `address` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `checkout`
  ADD PRIMARY KEY (`id`);

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `checkout_id` int(11) NOT NULL,
  `method` varchar(50) NOT NULL,
  `card_holder_name` varchar(200) NOT NULL,
  `card_number` varchar(16) NOT NULL,
  `card_expiry` varchar(20) NOT NULL,
  `card_cvv` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);