<?php
    namespace HotelClasses;
    class User {
        private $id;
        private $title;
        private $firstName;
        private $lastName;
        private $email;
        private $phone;
        private $address;
        private $password;
        private $passwordHashed;

        function hashPassword() {
            $this->passwordHashed = password_hash($this->password, PASSWORD_DEFAULT);
        }

        function getId() {
            return $this->id;
        }

        function getTitle() {
            return $this->title;
        }

        function getFirstName() {
            return $this->firstName;
        }

        function getLastName() {
            return $this->lastName;
        }

        function getEmail() {
            return $this->email;
        }

        function getPhone() {
            return $this->phone;
        }

        function getPassword() {
            return $this->password;
        }

        function getPasswordHashed() {
            return $this->passwordHashed;
        }

        function getAddress() {
            return $this->address;
        }

        function setId($id) {
            $this->id = $id;
        }

        function setTitle($title) {
            $this->title = $title;
        }
        
        function setFirstName($firstName) {
            $this->firstName = $firstName;
        }

        function setLastName($lastName) {
            $this->lastName = $lastName;
        }

        function setEmail($email) {
            $this->email = $email;
        }

        function setPhone($phone) {
            $this->phone = $phone;
        }

        function setPassword($password) {
            $this->password = $password;
        }

        function setPasswordHashed($passwordHashed) {
            $this->passwordHashed = $passwordHashed;
        }

        function setAddress($address) {
            $this->address = $address;
        }
    }