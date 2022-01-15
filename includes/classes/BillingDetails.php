<?php

class BillingDetails
{
    public static function insertDetails($connection, $agreement,$token, $username)
    {
        $query = $connection->prepare("INSERT INTO billingdetails (agreement, nextBillingDate, token, username)
                                VALUES(:agreement, :nextBillingDate, :token, :username)");

        $agreementDetails = $agreement->getAgreementDetails();

        $query->bindValue(":agreement", $agreement->getId());
        $query->bindValue(":nextBillingDate", $agreementDetails->getNextBillingDate());
        $query->bindValue(":token", $token);
        $query->bindValue(":username", $username);


        return $query->execute();

                
    }
}



?>