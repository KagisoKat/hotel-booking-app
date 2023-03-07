<?php
    namespace HotelClasses;
    class Room{
        private $roomId;
        private $roomNumber;
        private $hotelId;
        private $roomPictures = array();

        function getId() {
            return $this->roomId;
        }

        function getNumber() {
            return $this->roomNumber;
        }

        function getHotelId() {
            return $this->getHotelId;
        }

        function setId($roomId) {
            $this->roomId = $roomId;
        }

        function setNumber($roomNumber) {
            $this->roomNumber = $roomNumber;
        }

        function setHotelId($hotelId) {
            $this->hotelId = $hotelId;
        }

        function addPicture($roomPicture) {
            array_push($this->roomPictures, $roomPicture);
        }

        function removePicture($roomPicture) {
            foreach($this->roomPictures as $num => $picture) {
                if ($this->roomPicutres[$num] == $roomPicture)
                    unset($this->roomPictures[$num]);
            }
        }

        function getPictureArray() {
            return $this->roomPictures;
        }
    }
?>