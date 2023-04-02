<?php
    namespace HotelClasses;
    class Booking {
        private $id;
        private $user;
        private $hotel;
        private $room;
        private $startDate;
        private $endDate;
        private $price;
        private $status;

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

        function getNights() {
            return round((strtotime($this->endDate) - strtotime($this->startDate)) / 86400);
        }

        function getPrice() {
            return $this->getNights() * $this->getRoom()->getPrice();
        }

        function getStatus() {
            $currentTime=time();
            if ($this->status == 'active') {
                if ($currentTime > strtotime($this->endDate)) {
                    return 'expired';
                } elseif (($currentTime < strtotime($this->endDate)) && ($currentTime > strtotime($this->startDate))) {
                    return 'active';
                }
                return 'pending';
            }
            return $this->status;
        }

        function getCancellable() {
            $currentTime=time();
            $diff=(strtotime($this->getStartDate()) - $currentTime) / 60 / 60;
            if ($diff < 48 || $this->getStatus() == 'cancelled' || $this->getStatus() == 'expired' || $this->getStatus() == 'active') {
                return false;
            } else {
                return true;
            }
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

        function setStatus($status) {
            $this->status = $status;
        }
    }
?>