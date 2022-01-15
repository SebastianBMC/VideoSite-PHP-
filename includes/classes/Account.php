<?php 

    class Account
    {
        private $con;
        private $errorArray = array();


        public function __construct($con)
        {
            $this->con = $con;
        }

        public function register($fn, $ln, $un,$em, $em2, $pw, $pw2, )
        {
            $this->validateFirstName($fn);
            $this->validateLastName($ln);
            $this->validateUserName($un);
            $this->validateEmail($em, $em2);
            $this->validatePasswords($pw, $pw2);

            if(empty($this->errorArray))
            {
                return $this->insertUserDetails($fn, $ln, $un, $em, $pw);
            }
            return false;
        }

        public function login($un, $pw)
        {
            $pw = hash("sha512", $pw);
            $query = $this->con->prepare("SELECT * FROM users WHERE username=:un AND password=:pw"); 
            


            $query->bindValue(":un", $un);

            $query->bindValue(":pw", $pw);
        

            $query->execute();
            if($query->rowCount()==1)
            {
                return true;
            }

            array_push($this->errorArray, Constants::$loginFailed);
            return false;
        }

        private function insertUserDetails($fn, $ln, $un, $em, $pw)
        {
            $pw = hash("sha512", $pw);


            $query = $this->con->prepare("INSERT INTO users (firstName, lastName, username, email, password) 
                                          VALUES (:fn, :ln, :un, :em, :pw)");

            $query->bindValue(":fn", $fn);
            $query->bindValue(":ln", $ln);
            $query->bindValue(":un", $un);
            $query->bindValue(":em", $em);
            $query->bindValue(":pw", $pw);

            return $query->execute();
        }


        private function validateFirstName($firstName)
        {
            if(strlen($firstName) < 2 || strlen($firstName) > 25)
            {
                array_push($this->errorArray, Constants::$fistNameCharacters);
            }
        }
        private function validateLastName($lastName)
        {
            if(strlen($lastName) < 2 || strlen($lastName) > 25)
            {
                array_push($this->errorArray, Constants::$lastNameCharacters);
            }
        }
        private function validateUserName($userName)
        {
            if(strlen($userName) < 2 || strlen($userName) > 25)
            {
                array_push($this->errorArray, Constants::$usernameCharacters);
                return;
            }

            $query = $this->con->prepare("SELECT * FROM users WHERE username=:userName");
            $query->bindValue(":userName", $userName);

            $query->execute();

            if($query->rowCount() != 0)
            {
                array_push($this->errorArray, Constants::$usernameTaken);
            }

        }
        private function validateEmail($email, $email2)
        {
            if($email != $email2)
            {
                array_push($this->errorArray, Constants::$emailsDontMatch);
                return;
            }
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                array_push($this->errorArray, Constants::$emailInvalid);
                return;
            }
            $query = $this->con->prepare("SELECT * FROM users WHERE email=:email");
            $query->bindValue(":email", $email);

            $query->execute();

            if($query->rowCount() != 0)
            {
                array_push($this->errorArray, Constants::$emailInUse);
            }
        }

        public function validatePasswords($pw, $pw2)
        {
            if($pw != $pw2)
            {
                array_push($this->errorArray, Constants::$passwordsDontMatch);
                return;
            }
            if(strlen($pw) < 8 || strlen($pw) > 25)
            {
                array_push($this->errorArray, Constants::$passwordLength);
            }
        }

        public function getError($error)
        {
            if(in_array($error, $this->errorArray))
            {
                return "<span class='errorMessage'>$error</span>";
            }
        }

        public function updateDetails($fn, $ln, $em, $un)
        {
            $this->validateFirstName($fn);
            $this->validateLastName($ln);
            $this->validateNewEmail($em, $un);

            if(empty($this->errorArray))
            {
                $query = $this->con->prepare("UPDATE users SET firstName=:fn, lastName=:ln
                ,email=:em WHERE username=:un");

                $query->bindValue(":fn", $fn);
                $query->bindValue(":ln", $ln);
                $query->bindValue(":em", $em);
                $query->bindValue(":un", $un);

                return $query->execute();
            }

            return false;
        }

        private function validateNewEmail($email, $un)
        {
        
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                array_push($this->errorArray, Constants::$emailInvalid);
                return;
            }
            $query = $this->con->prepare("SELECT * FROM users WHERE email=:email AND username != :un");
            $query->bindValue(":email", $email);
            $query->bindValue(":un", $un);


            $query->execute();

            if($query->rowCount() != 0)
            {
                array_push($this->errorArray, Constants::$emailInUse);
            }
        }

        public function getFirstError()
        {
            if(!empty($this->errorArray))
            {
                return $this->errorArray[0];
            }


        }

        public function updatePassword($oldPw, $pw, $pw2, $un)
        {
            $this->validateOldPassword($oldPw, $un);
            $this->validatePasswords($pw, $pw2);
            if(empty($this->errorArray))
            {
                $query = $this->con->prepare("UPDATE users SET password=:pw
                 WHERE username=:un");

                $pw = hash("sha512", $pw);

                $query->bindValue(":pw", $pw);
         
                $query->bindValue(":un", $un);

                return $query->execute();
            }

            return false;


        }

        public function validateOldPassword($oldPw, $un)
        {
            $pw = hash("sha512", $oldPw);
            $query = $this->con->prepare("SELECT * FROM users WHERE username=:un AND password=:pw"); 
            


            $query->bindValue(":un", $un);

            $query->bindValue(":pw", $pw);
        

            $query->execute();


            if($query->rowCount() == 0)
            {
                array_push($this->errorArray, Constants::$passwordIncorrect);
            }
        }


    }


?>