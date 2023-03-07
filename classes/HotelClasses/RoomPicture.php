<?php
    namespace HotelClasses;
    class RoomPicture {
        private $id;
        private $filename;

        function getId() {
            return $this->id;
        }

        function getFilename() {
            return $this->filename;
        }

        function setId($id) {
            $this->id = $id;
        }

        function setFilename($filename) {
            $this->filename = $filename;
        }
    }
?>