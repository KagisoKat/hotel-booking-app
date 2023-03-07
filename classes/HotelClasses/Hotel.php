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

        function addRoom($room) {
            array_push($this->rooms, $room);
        }

        function removeRoom($roomId) {
            foreach($this->rooms as $num => $room) {
                if ($this->rooms[$num].getRoomId() == $roomId)
                    unset($this->rooms[$num]);
            }
        }

        function getRoomArray() {
            return $this->rooms;
        }
    }
?>