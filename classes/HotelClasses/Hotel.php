<?php
    namespace HotelClasses;
    class Hotel{
        private $id;
        private $name;
        private $address;
        private $rating;
        private $pictures = array();
        private $rooms = array();

        function getId() {
            return $this->id;
        }

        function getName() {
            return $this->name;
        }

        function getAddress() {
            return $this->address;
        }

        function getRating() {
            return $this->rating;
        }

        function setId($id) {
            $this->id = $id;
        }

        function setName($name) {
            $this->name = $name;
        }

        function setAddress($address) {
            $this->address = $address;
        }

        function setRating($rating) {
            $this->rating = $rating;
        }

        function addPicture($picture) {
            array_push($this->pictures, $picture);
        }

        function removePicture($pictureId) {
            foreach($this->pictures as $num => $picture) {
                if ($this->pictures[$num] == $pictureId)
                    unset($this->pictures[$num]);
            }
        }

        function getPictureArray() {
            return $this->pictures;
        }

        function addRoom($hotelRoom) {
            array_push($this->hotelRooms, $hotelRoom);
        }

        function removeRoom($hotelRoomId) {
            foreach($this->hotelRooms as $num => $room) {
                if ($this->hotelRooms[$num].getRoomId() == $hotelRoomId)
                    unset($this->hotelRooms[$num]);
            }
        }

        function getRoomArray() {
            return $this->hotelRooms;
        }
    }
?>