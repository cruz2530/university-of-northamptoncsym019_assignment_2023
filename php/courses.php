<?php

    require_once('db.php');

        {
            $this->conn = Database::releaseConnection($this->conn);
        }


    }

?>