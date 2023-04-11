<?php
    namespace HotelClasses;
    class Hotel{
        private $id;
        private $name;
        private $address;
        private $rating;
        private $pictures = array();
        private $rooms = array();

        public function getId() {
            return $this->id;
        }

        public function getName() {
            return $this->name;
        }

        public function getAddress() {
            return $this->address;
        }

        public function getRating() {
            return $this->rating;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setName($name) {
            $this->name = $name;
        }

        public function setAddress($address) {
            $this->address = $address;
        }

        public function setRating($rating) {
            $this->rating = $rating;
        }

        public function addPicture($picture) {
            array_push($this->pictures, $picture);
        }

        public function removePicture($pictureId) {
            foreach($this->pictures as $num => $picture) {
                if ($this->pictures[$num] == $pictureId)
                    unset($this->pictures[$num]);
            }
        }

        public function getPictureArray() {
            return $this->pictures;
        }

        public function addRoom($room) {
            array_push($this->rooms, $room);
        }

        public function removeRoom($roomId) {
            foreach($this->rooms as $num => $room) {
                if ($this->rooms[$num].getRoomId() == $roomId)
                    unset($this->rooms[$num]);
            }
        }

        public function getRoomArray() {
            return $this->rooms;
        }

        public static function compareRoomPrices($hotel1, $hotel2) {
            $difference = $hotel1->getRoomArray()[0]->getPrice() - $hotel2->getRoomArray()[0]->getPrice();
            if ($difference < 0) {
                return "<span style=\"color: red;\">\u{25b2}" . abs($difference). '</span>';
            } elseif ($difference > 0) {
                return "<span style=\"color: blue;\">\u{25bc}" . abs($difference) . '</span>';
            }
            return '<span style="color: black;">' . abs($difference). '</span>';
        }

        //public static function compareHotelRatings($hotel1, $hotel2) {
        public static function compareHotelRatings($hotel1, $hotel2) {
            $difference = $hotel1->getRating() - $hotel2->getRating();
            $returnString = "<span style=\"color: ";
            if ($difference > 0) {
                $returnString .= "red;\">\u{25bc}";
            } elseif ($difference < 0) {
                $returnString .= "blue;\">\u{25b2}";
            } else {
                $returnString .= "black;\">";
            }
            for ($starCount = 0; $starCount < abs($difference); $starCount++) {
                $returnString .= "\u{272D}";
            } 
            $returnString .= '</span>';
            return $returnString;
        }
    }
?>