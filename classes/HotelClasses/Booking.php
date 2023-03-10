<?php
    namespace HotelClasses;
    class Booking {
        private $id;
        private $user;
        private $hotel;
        private $room;
        private $startDate;
        private $endDate;

        function getId() {
            return $this->id;
        }

        function getUser() {
            return $this->user;
        }

        function getHotel() {
            return $this->hotel;
        }

        function getRoom() {
            return $this->room;
        }

        function getStartDate() {
            return $this->startDate;
        }

        function getEndDate() {
            return $this->endDate;
        }

        function setId($id) {
            $this->id = $id;
        }

        function setUser($user) {
            $this->user = $user;
        }

        function setHotel($hotel) {
            $this->hotel = $hotel;
        }

        function setRoom($room) {
            $this->room = $room;
        }

        function setStartDate($startDate) {
            $this->startDate = $startDate;
        }

        function setEndDate($endDate) {
            $this->endDate = $endDate;
        }
    }
?>