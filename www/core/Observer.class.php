<?php
    namespace App\core;

    interface Observer{
        public function alert($mail, $message);
    }