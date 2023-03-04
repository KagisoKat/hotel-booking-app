<?php
    namespace HotelClasses;
    class Staff {
        private int $id;
        private $firstName;
        private $lastName;
        private $email;
        private $password;
        private $passwordHashed;

        function hashPassword() {
            $this->passwordHashed = password_hash($this->password, PASSWORD_DEFAULT);
        }

        function getId() {
            return $this->id;
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

        function getPassword() {
            return $this->password;
        }

        function getPasswordHashed() {
            return $this->passwordHashed;
        }

        function setId($id) {
            $this->id = $id;
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

        function setPassword($password) {
            $this->password = $password;
        }

        function setPasswordHashed($passwordHashed) {
            $this->passwordHashed = $passwordHashed;
        }
   }