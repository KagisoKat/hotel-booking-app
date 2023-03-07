<?php
    namespace HotelClasses;
    class Room{
        private $id;
        private $label;
        private $price;
        private $hotelId;
        private $pictures = array();

        function getId() {
            return $this->id;
        }

        function getLabel() {
            return $this->label;
        }

        function getPrice() {
            return $this->price;
        }

        function getHotelId() {
            return $this->hotelId;
        }

        function setId($id) {
            $this->id = $id;
        }

        function setLabel($label) {
            $this->label = $label;
        }

        function setPrice($price) {
            $this->price = $price;
        }

        function setHotelId($hotelId) {
            $this->hotelId = $hotelId;
        }

        function addPicture($picture) {
            array_push($this->proomPictures, $picture);
        }

        function removePicture($picture) {
            foreach($this->pictures as $num => $picture) {
                if ($this->picutres[$num] == $picture)
                    unset($this->pictures[$num]);
            }
        }

        function getPictureArray() {
            return $this->pictures;
        }
    }
?>